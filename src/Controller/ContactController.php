<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
  #[Route('/profile/contact', name: 'app_contact')]
  public function index(Request $request, MailerInterface $mailer): Response
  {
    // Récupère l'email de l'utilisateur actuel
    $user = $this->getUser();
    $userEmail = $user ? $user->getEmail() : null;

    // Récupère l'email de l'administrateur depuis les paramètres d'environnement
    $adminEmail = $_ENV['ADMIN_EMAIL'];

    // Crée un formulaire de type ContactType
    $contactForm = $this->createForm(ContactType::class);

    // Gère la soumission du formulaire
    $contactForm->handleRequest($request);

    if ($contactForm->isSubmitted() && $contactForm->isValid()) {
      // Si le formulaire est soumis et valide, récupère les données du formulaire
      $data = $contactForm->getData();
      $message = $data['message'];

      // Crée un email templatisé
      $email = (new TemplatedEmail())
        ->from($adminEmail)
        ->to($adminEmail)
        ->replyTo($userEmail) // Utilise l'email de l'utilisateur comme adresse de réponse
        ->subject("Message from a user on the Chuko Manga site")
        ->htmlTemplate('contact/email.html.twig')  // Utilise un template Twig pour le contenu HTML de l'email
        ->context([
          'message' => $message,
          'userEmail' => $userEmail,
        ]);

      // Envoie l'email via le service de messagerie (MailerInterface)
      $mailer->send($email);

      // Ajoute un message flash pour indiquer que le message a été envoyé avec succès
      $this->addFlash('send', 'Your message has been sent successfully.');

      // Redirige vers la page de confirmation après l'envoi du message
      return $this->redirectToRoute('app_contact_confirmation');
    }

    // Rendre le formulaire s'il n'est pas soumis ou n'est pas valide
    return $this->render('contact/index.html.twig', [
      'contactForm' => $contactForm->createView(),
    ]);
  }

  #[Route('/profile/contact/confirmation', name: 'app_contact_confirmation')]
  public function confirmation(): Response
  {
    // Rendre la page de confirmation de l'envoi du message
    return $this->render('contact/confirmation.html.twig');
  }
}
