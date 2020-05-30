<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
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


}
