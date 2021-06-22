<?php

namespace App\Controller;

use App\Form\PostType;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PublicationController extends AbstractController
{
    #[Route('/publication', name: 'publication')]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {

        $article =new Article();
        $form=$this->CreateForm(PostType::class ,$article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $article->setCreateAt(new \DateTime());
            $manager->persist($article);
            $manager->flush();
            $this->addFlash('success' ,'Publication Créer avec succeés !');
           }

        return $this->render('publication/index.html.twig', [
            'form' =>$form->createView()
        ]);
    }
}
