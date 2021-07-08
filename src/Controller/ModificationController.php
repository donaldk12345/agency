<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModificationController extends AbstractController
{
      /**
       * 
     * @Route("/modification",name="modification")
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        $articles=$this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('modification/index.html.twig', [
            'articles' => $articles
        ]);
    }
      /**
     * @Route("/edit/{id}",name="edit")
     * @IsGranted("ROLE_USER")
     */
    public function edit(Article $article ,Request $request, EntityManagerInterface $manager , CacheManager $cacheManager, UploaderHelper $helper){
        
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
      /**
     * @Route("/delete/{id}",name="delete")
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request,Article $article ,EntityManagerInterface $manager, CacheManager $cacheManager, UploaderHelper $helper){
        {
           
        
           
        }
       
        $this->addFlash('success' ,' Votre Article à été bien suprimée !');
         return $this->redirectToRoute('home');

    }
     /**
      * @Route("/view/{id}",name="view")
      * @IsGranted("ROLE_USER") 
      * @param Article $article
      * @return Response
      */
    public function view(Article $article , Request $request, EntityManagerInterface $manager){
       
        $comment= new Comment();
        
        $form=$this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
          $comment->setCreatedAt(new \DateTime())
                  ->setArticle($article);
                  $manager->persist($comment);
                  $manager->flush();
                  return $this->redirectToRoute('view',[
                    'id'=>$article->getId()
                  ]);
        }


        return $this->render('modification/view.html.twig', [
            'article' => $article,
            'commentForm'=> $form->createView() 
        ]);
    

    }
    
}
