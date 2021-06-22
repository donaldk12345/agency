<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, \Swift_Mailer $mailer): Response
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contact= $form->getData();

            $message = (new \Swift_Message('You Got Mail from Symfony 4!'))
                ->setFrom($contact['email'])
                ->setTo('fotsoyvesdonald@gmail.com')
                ->setBody(
                    $contact['message'],
                    'text/plain'
                )
            ;

            $mailer->send($message);

            $this->addFlash('success', 'Votre message à été bien envoyer ');

            return $this->redirectToRoute('contact');
           }
        return $this->render('contact/index.html.twig', [
            'form' =>$form->createView()
        ]);
    }
}

