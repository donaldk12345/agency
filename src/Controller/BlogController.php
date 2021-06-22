<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
   
    /**
     * @Route("/blog",name="blog")
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        $articles=$this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('blog/index.html.twig', [
            'articles' => $articles
        ]);
    }
    /**
     * @Route("/",name="home")
     * @IsGranted("ROLE_USER")
     */
    
    public function home(){
        return $this->render('blog/home.html.twig', [
            'controller_name' => 'BlogController',
        ]);

    }
    
}
