<?php

namespace App\Controller;

use Swift_Mailer;
use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
      /**
     * @Route("/contact",name="contact")
     * @IsGranted("ROLE_USER")
     *  @param MailerInterface $mailer
     * @return Response
     */
    public function index(Request $request,MailerInterface $mailer): Response
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contact= $form->getData();

            $message= (new Email())
                ->From($contact['email'])
                ->To('fotsoyvesdonald@gmail.com')
                ->text(
                    $contact['message'],
                    'text'
                )
            ;

            $mailer->send($message);

            $this->addFlash('success', 'Votre message à été bien envoyer ');

            return $this->redirectToRoute('home');
           }
        return $this->render('contact/index.html.twig', [
            'form' =>$form->createView()
        ]);
    }
}

