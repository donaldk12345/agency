<?php

namespace App\Controller;

use App\Entity\Article;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $donnees=$this->getDoctrine()->getRepository(Article::class)->findAll();
        $articles= $paginator->paginate(
        $donnees,
        $request->query->getInt('page',1),
        6
        );
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
