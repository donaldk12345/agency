<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\Mime\Email;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{
    
    #[Route('/inscription', name: 'security_registration')]
    public function registration( Request $request , EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder,\Swift_Mailer $mailer){
         $user= new User();
         $form=$this->createForm(RegistrationType::class ,$user);
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) { 
         $hach=$encoder->encodePassword($user, $user->getPassword());
         $user->setPassword($hach);
         $manager->persist($user);
         $manager->flush();

         $email =(new \Swift_Message())
              ->setfrom('fotsoyvesdonald@gmail.com')
              ->setto($user->getEmail())
              ->setsubject('Bienvenue sur le site')
              ->setBody("Merci pour votre Insciption {$user->getUserName()} ");
         $mailer->send($email);

         $this->addFlash('success' ,' Votre compte à été Créer avec succeés !');
         return $this->redirectToRoute('security_login');
         }
         return $this->render('security/registration.html.twig' ,[
         'form'=>$form->createView()
         ]);

    }
    #[Route('/connexion', name: 'security_login')]
    public function login(){
        return $this->render('security/login.html.twig');
        
    }
    #[Route('/deconnexion', name: 'security_logout')]
    public function logout(){
        
    }
   
}
