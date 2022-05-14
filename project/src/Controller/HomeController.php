<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
           $email = (new TemplatedEmail())
               ->from(new Address($data['mail']))
               ->to(new Address('mariage.manon.xavier@gmail.com', 'Mariage Manon & Xavier'))
               ->subject('Formulaire de contact Mariage')
               ->htmlTemplate('message/contact.html.twig')
               ->context([
                   'contact' => $data['mail'],
                   'subject' => $data['subject'],
                   'description' => $data['description']
               ]);

           $mailer->send($email);
            $this->addFlash(
                'success',
                'Votre message a bien été envoyé. Quelqu\'un y répondra dès que possible'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('home/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mentions", name="mentions")
     */
    public function mentions(): Response
    {
        return $this->render('home/mentions.html.twig');
    }
}
