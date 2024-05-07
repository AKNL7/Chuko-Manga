<?php

namespace App\Controller;

use App\Form\ContactType;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/profile/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        //on recupere les données du User
        $userFirstName = ucfirst($this->getUser()->getFirstName()); 
        $userLastName = ucfirst($this->getUser()->getLastName());
        $userEmail = ucfirst($this->getUser()->getEmail());

        $adminEmail = $_ENV['ADMIN_EMAIL'];

       
       
        $contactForm = $this->createForm(ContactType::class);

        // Handle the form submission
        $contactForm->handleRequest($request);

        if($contactForm->isSubmitted() && $contactForm->isValid()) {
          $this->addFlash("send", "Vote message à bien été envoyé");
        $data=$contactForm->getData();
        $message = $data['message']; 
        $mailer->send((new TemplatedEmail())
       ->from($adminEmail)
       ->to($adminEmail)
       ->replyTo($userEmail)
       ->subject("Message provenant d'un utilisateur du site Chuko Manga")
       ->htmlTemplate('contact/email.html.twig')
       ->context([
        "message" => $message,
        "firstName" => $userFirstName,
        "lastName" => $userLastName,
        "userEmail" => $userEmail,
     ])
  );
      }
      
        // Render the index template with the contact form
    
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contactForm' => $contactForm->createView(),
          ]);
   }
}   
    


