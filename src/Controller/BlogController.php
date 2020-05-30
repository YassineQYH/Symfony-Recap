<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article ;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Article::class); // Je crée une variable $repo dans ma function index() et je lui dit que je veux discuter avec Doctrine et je veux que tu me donne un Repository, celui qui gère l’entité Article.
        // Attention, si j’utilise la classe Article, je vais devoir expliqué à PHP où elle se trouve en utilisant le use et je sais qu’elle est dans App\Entity\Article ;

        // $article = $repo->find(12) ; // à ce moment la il irait me trouver l’article n°12 et il me le filerait dans Article.
        // $article = $repo->findOneByTitle("Titre de l'article"); // Ça irait chercher un article dont le titre serait celui-ci ("Titre de l'article");
        // $articles = $repo->findByTitle("Titre de l'article"); // Et donc je vais trouver tout les articles qui ont ce titre.
        $articles = $repo->findAll(); // Pour trouver tout mes articles 

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }


    // Je dois lier cette fonction à une adresse bien particulière avec une annotation. Utiliser des guillemet et non pas des simples cote ! c'est important !
    // "/"  => C'est la route
    // name="home" => C'est le nom de la route

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig', // Permet d'appeler un fichier .twig pour pouvoir l'afficher. (nom/l'adresse du fichier .twig que je veux appeler.)
        // Je dois maintenant créer un fichier home.html.twig au sein de mon dossier blog et je ne vais y mettre pour le moment que un h1 avec 2 <p> puis visiter http://localhost:8000/
        [
        'title' => "Bienvenu ici les amis !",
        'age' => 31
        ]); 
    }   

    /**
     * @Route("/blog/12", name="blog_show")
     */
    public function show()
    {
        return $this->render('blog/show.html.twig');
    }

}
