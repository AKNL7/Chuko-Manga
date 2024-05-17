<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ContactType;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;


class ContactController extends AbstractController
{
    #[Route('/profile/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager, User $user): Response
    {
    //on recupere les données du User
    $userEmail = new User();
        $userEmail = ucfirst($this->getUser()->getEmail());

        $adminEmail = $_ENV['ADMIN_EMAIL'];

       
       
        $contactForm = $this->createForm(ContactType::class);

        // Handle the form submission
        $contactForm->handleRequest($request);

        if($contactForm->isSubmitted() && $contactForm->isValid()) {
          $this->addFlash("send", "Vote message à bien été envoyé");
        $data=$contactForm->getData();
        $message = $data['message']; 
        $email = $mailer->send((new TemplatedEmail())
       ->from($adminEmail)
       ->to($adminEmail)
       ->replyTo($userEmail)
       ->subject("Message provenant d'un utilisateur du site Chuko Manga")
       ->htmlTemplate('contact/email.html.twig')
       ->context([
        "message" => $message,
    
        "userEmail" => $userEmail,
     ])
    );
    $mailer->send($email);
      }
      
        // Render the index template with the contact form
    
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contactForm' => $contactForm->createView(),
          ]);
   }
}   
    


