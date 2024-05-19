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
    // Get the current user's email
    $user = $this->getUser();
    $userEmail = $user ? $user->getEmail() : null;
    $adminEmail = $_ENV['ADMIN_EMAIL'];

    $contactForm = $this->createForm(ContactType::class);

    // Handle the form submission
    $contactForm->handleRequest($request);

    if ($contactForm->isSubmitted() && $contactForm->isValid()) {
      $data = $contactForm->getData();
      $message = $data['message'];

      $email = (new TemplatedEmail())
        ->from($adminEmail)
        ->to($adminEmail)
        ->replyTo($userEmail)
        ->subject("Message from a user on the Chuko Manga site")
        ->htmlTemplate('contact/email.html.twig')
        ->context([
          'message' => $message,
          'userEmail' => $userEmail,
        ]);

      $mailer->send($email);

      $this->addFlash('send', 'Your message has been sent successfully.');

      return $this->redirectToRoute('app_contact_confirmation');
    }

    // Render the form if it is not submitted or not valid
    return $this->render('contact/index.html.twig', [
      'contactForm' => $contactForm->createView(),
    ]);
  }

  #[Route('/profile/contact/confirmation', name: 'app_contact_confirmation')]
  public function confirmation(): Response
  {
    return $this->render('contact/confirmation.html.twig');
  }
}
