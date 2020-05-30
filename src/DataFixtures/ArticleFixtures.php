<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++)    // Je crée 10 articles
        {
            $article = new Article(); // Pour créer un article, je vais tout simplement instancier une classe $article qui sera égaleà new Article
                // Attention, dans Symfony dés qu'on utilise le nom d'une classe, il ne faut pas oublier de rajouter un use en haut (une sorte de require_once même si ce n'est pas vrmt ça) Pour expliqué à PHP d'où vient la classe Article. Elle vient de la classe App\Entity\Article; Pour être sur de son chemin je vais voir dans ma classe Article qui représente un article avec son titre son contenu etc dans le fichier Article.php du dossier Entity et je regarde où elle est (donc son namespace se trouve dans App\Entity et elle s'appel Article)
                // Maintenant, je peut commencer à écrire des choses dans mon article.
                $article->setTitle("Titre de l'article n°$i")  // ASTUCE : Utilisez Faker ! <=> Utiliser la librairie Faker pour créer de fausse données !
                // Ce qui est cool avec les setters que Symfony m'a généré c'est que je peux les enchainer.
                ->setContent("<p>Contenu de l'article n°$i</p>")
                ->setImage("http://placehold.it/350x150")
                ->setCreatedAt(new \DateTime()); // Attention createdAt est une propriété de type datetime il faut donc que je lui passe un objet datetime de php en lui disant new \DateTime() | Pourquoi un \ ? Car les classes dans Symfony doient toute faire partie d'un espace de nom (un namespace). Ici par exemple ArticleFixture fait partie du namespace App\DataFixtures. Et ici, datetime, j'aimerais bien préciser que c'est une classe qui fait partie du namespace global de PHP. Donc je met un \ devant.

                // Attention le fait de créer un article ici ne veut pas dire qu'il existe dans la BDD. 
                // Pour ça, il faut faire 2 choses.

                //  Je dois demander à mon Manager de se préparer pour faire persister dans le temps mon article
                $manager->persist($article);    // Le fait de faire persister c'est une préparation à le faire persister mais ce n'est pas le fait qu'il éxiste réellement dans la BDD.

        }
        // Si je veux que mes 10 articles que je prépare à faire persister à chaque fois soit vraiment dans la BDD
        $manager->flush();  //  Donc je dois utiliser ceci <=> La fonction flush() <=> balance réellement la requête SQL qui mettra en place les différentes manipulation que j'ai fait ici.
    }
}   // php bin/console doctrine:fixtures :load <=> Charge toutes nos fixtures dans la base ! 
