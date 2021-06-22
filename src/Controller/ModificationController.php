<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModificationController extends AbstractController
{
    #[Route('/modification', name: 'modification')]
    public function index(): Response
    {
        $articles=$this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('modification/index.html.twig', [
            'articles' => $articles
        ]);
    }
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Article $article ,Request $request, EntityManagerInterface $manager){
        
      $form=$this->createFormBuilder($article)
      ->add('titre')
      ->add('content',TextareaType::class)
      ->add('imageFile',VichImageType::class ,[
          'required' => false,
      ])
      ->getForm()
      ;
      $form ->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()){
          $manager->flush();
          return $this->redirectToRoute('home');
      }
      return $this->render('edit/edit.html.twig', [
        'form' =>$form->createView()
    ]);

    }
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Request $request,Article $article ,EntityManagerInterface $manager){
        if($this->isCsrfTokenValid('delete' . $article->$this->getId(), $request->request->get('csrf_token'))){
        
            $manager->remove($article);
            $manager->flush();
        }
       
        $this->addFlash('success' ,' Votre Article à été bien suprimée !');
         return $this->redirectToRoute('home');

    }
}
