<?php

namespace App\Controller;

use App\Form\ContactType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $contactForm = $this->createForm(ContactType::class);

        // Handle the form submission
        $contactForm->handleRequest($request);
       
        // Render the index template with the contact form
    
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contactForm' => $contactForm->createView(),
        ]);
    }
}
