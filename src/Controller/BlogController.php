<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo) // Si je dis ici que j’ai besoin d’une classe ArticleRepository, il ne pas oublier d’ajouter le use App\Repository\ArticleRepository;
    // Du coup, je n’ai plus du tout besoin de cette ligne : $repo = $this->getDoctrine()->getRepository(Article::class);
    // Puisque dans cette ligne, je demandais à Doctrine de me donner un repository mais je n’en ai plus besoin car Symfony, grâce au système d’injection de dépendance qui existe dans d’autres Framework aussi évidemment, comme dans Angular par exemple, a compris que quand il appellerait index, il passerait à cette fonction un Repository dans la variable $repo 
    {
        // $repo = $this->getDoctrine()->getRepository(Article::class); // Je crée une variable $repo dans ma function index() et je lui dit que je veux discuter avec Doctrine et je veux que tu me donne un Repository, celui qui gère l’entité Article.
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



    // ROUTES PARAMETREES <=> Intégrer des paramètres variables dans une route.

    /**
     * @Route("/blog/{id}", name="blog_show")
     */

    // Et là, Symfony comprend que dans une route qui est blog/quelque chose, ce quelque chose, c’est l’identifiant.
    // Pour récupérer cet identifiant, Symfony va le passer à ma fonction show()

    public function show(/* ArticleRepository $repo, $id */ Article $article)
    {
        // Il me reste à créer un repository en disant
        // $repo = $this->getDoctrine()->getRepository(Article::class); // Je veux parler avec Doctrine, je veux avoir un repository, le quel me demande doctrine donc je lui dit Article::class // Je peux me passer de cette ligne-là tout simplement en demandant la dépendance, je vais demander à Symfony de me passer un repo qui serait un ArticleRepository et là ça marcherait puisqu’ici 

        // J’utilise $repo pour trouver l’article qui à cette identifiant-là.
        // $article = $repo->find($id); // Trouve moi l’article qui à l’identifiant qu’on m’a envoyé dans l’adresse en haut. // Je peux carrément me passer de cette ligne qui crée l’article Et tout simplement demander à Symfony de me passer ici une variable article qui sera de type $Article

        return $this->render('blog/show.html.twig', [   // Je dois maintenant passer un tableau à twig avec les variables que je veux qu’il utilise, notamment ici la seul variable qui m’intéresse c’est de dire à twig : tu vas devoir utiliser un article dans ton template et il contiendra les données de mon article. 
            'article' => $article
        ]); // Je peux donc maintenant aller dans ma vue qui s’appelle show.html.twig et je sais que maintenant dans tout ce template j’ai accès à une variable qui s’appelle article. Je peux donc dynamiser mon article.
    }

} 