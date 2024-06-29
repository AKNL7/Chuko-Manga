<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
         // Création d'une nouvelle instance de l'entité User
        $user = new User();

         // Création du formulaire d'inscription lié à l'entité User
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                 // Hashage du mot de passe plain
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
         // Attribution d'un rôle à l'utilisateur   
        $user->setRoles(["ROLE_USER"]);
            $entityManager->persist($user);
            $entityManager->flush();

            // Envoi d'un email de confirmation pour le lien d'activation
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('contact@chuko-manga.com', 'Chuko Manga'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            
 // Redirection vers la page de vérification d'email après l'enregistrement
            return $this->redirectToRoute('app_verify');
        }

            // Affichage du formulaire d'inscription
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        // Vérification de l'accès complet authentifié
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

   // Validation du lien de confirmation d'email, définit User::isVerified=true et persiste en base de données
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
              // Gestion des erreurs de vérification d'email
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

             // Redirection vers la page d'inscription en cas d'erreur
            return $this->redirectToRoute('app_register');
        }

// Message flash de succès pour l'utilisateur après vérification de l'email
        $this->addFlash('success', 'Votre adresse email a été verifiée.');

         // Redirection vers l'espace utilisateur après vérification de l'email
        return $this->redirectToRoute('app_my_space');
    }

    #[Route('/verify', name: 'app_verify')]
    public function verify(): Response
    {
         // Affichage de la page de vérification
        return $this->render('security/verify.html.twig');
    }
}

