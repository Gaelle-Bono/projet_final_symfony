<?php
namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
class ArticleController extends AbstractController
{
    /**
     * Lecture d'un article
     * 
     * @param   int     $id     Identifiant de l'article
     * 
     * @return Response
     */
    public function index(EntityManagerInterface $em, int $id): Response
    {
        // On récupère l'article qui correspond à l'id passé dans l'url
        $article = $em->getRepository(Article::class)->findBy(['id' => $id]);
        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }
    /**
     * Création / Modification d'un article
     * 
     * @param   int     $id     Identifiant de l'article
     * 
     * @return Response
     */
    public function edit(EntityManagerInterface $em, Request $request, int $id=null): Response
    {
        // Si un identifiant est présent dans l'url alors il s'agit d'une modification
        // Dans le cas contraire il s'agit d'une création d'article
        if($id) {
            $mode = 'update';
            // On récupère l'article qui correspond à l'id passé dans l'url
            $article = $em->getRepository(Article::class)->findBy(['id' => $id])[0];
        }
        else {
            $mode       = 'new';
            $article    = new Article();
        }
        //$categories = $em->getRepository(Category::class)->findAll();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->saveArticle($em, $article, $mode);
            return $this->redirectToRoute('article_edit', array('id' => $article->getId()));
        }
        $parameters = array(
            'form'      => $form->createView(),
            'article'   => $article,
            'mode'      => $mode
        );
        return $this->render('article/edit.html.twig', $parameters);
    }
    /**
     * Création / Modification d'un article
     * 
     * @param   int     $id     Identifiant de l'article
     * 
     * @return Response
     */
    public function remove(EntityManagerInterface $em, int $id): Response
    {
        // On récupère l'article qui correspond à l'id passé dans l'URL
        $article = $em->getRepository(Article::class)->findBy(['id' => $id])[0];
        // L'article est supprimé
        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute('homepage');
    }
    /**
     * Compléter l'article avec des informations avant enregistrement
     * 
     * @param   Article     $article
     * @param   string      $mode 
     * 
     * @return Article
     */
    private function completeArticleBeforeSave(Article $article, string $mode) {
        if($article->isPublished()){
            $article->setPublishedAt(new \DateTime());
        }
        $article->setAuthor($this->getUser());
        return $article;
    }
    /**
     * Enregistrer un article en base de données
     * 
     * @param   Article     $article
     * @param   string      $mode 
     */
    private function saveArticle(EntityManagerInterface $em, Article $article, string $mode){
        $article = $this->completeArticleBeforeSave($article, $mode);
        $em->persist($article);
        $em->flush();
        $this->addFlash('success', 'Enregistré avec succès');
    }
}
