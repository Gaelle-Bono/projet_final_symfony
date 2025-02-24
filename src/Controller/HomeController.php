<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

class HomeController extends AbstractController
{
    /**
     * Page d'accueil
     * 
     * @return  Response
     */
    public function index(EntityManagerInterface $em): Response
    {
        // On récupère tous les articles disponibles en base de données
        $articles   = $em->getRepository(Article::class)->findAll();
        return $this->render('home/index.html.twig', [
            'articles'  => $articles
        ]);
    }
}