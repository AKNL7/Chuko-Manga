<?php

namespace App\Controller;

use App\Form\ContactType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $userFirstName = ucfirst($this->getUser()->getFirstName()); 
        $userLastName = ucfirst($this->getUser()->getLastName());
        $userEmail = ucfirst($this->getUser()->getEmail());
       
       
        $contactForm = $this->createForm(ContactType::class, [
            'firstName' => $userFirstName,
            'lastName' => $userLastName,
            'email' => $userEmail,
        
        ]);

        // Handle the form submission
        $contactForm->handleRequest($request);

        if($contactForm->isSubmitted() && $contactForm->isValid()) {
            //Aprés avoir fait l'entité contact tester l'affichage du message 
            // Send the email
          
        }
       
        // Render the index template with the contact form
    
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contactForm' => $contactForm->createView(),
        ]);
    }
}
