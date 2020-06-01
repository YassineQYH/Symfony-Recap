<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

use Faker;
/* use Fake\Factory; */

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        // Créer 3 catégories fakées
        for($i = 1; $i <= 3; $i++)
        {
            // Je veux créer une variable category qui sera une nouvelle Category
            $category = new Category(); // use App\Entity\Category
            // Je vais commencer à la remplir
            $category->setTitle($faker->sentence()) // Je vais demander à faker de le générer lui même | sentence($nbWords = 6, $variableNbWords = true) => Il peut prendre en paramètre un nbr de mots qui est égale à 6 par défauts mais comme c'est variable, je peux avoir autant 2 mots que 6 mots, je peux aller maximum jusq'à 6.
                    ->setDescription($faker->paragraph()); // Un seul paragraphe et je peux lui donner un nombre de phrase, ici par défaut c'est 3.

            $manager->persist($category);

            // Créer entre 4 et 6 articles
            for ($j = 1; $j <= mt_rand(4,6) ; $j++) // Attention j'ai déjà utiliser $i plus haut donc j'en utilise une autre ici : j || md_rand => Fonction PHP qui me permet d'avoir un nbr au hasard
            {
                $article = new Article();

                $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '<p>';

                $article->setTitle($faker->sentence())
                        ->setContent($content) // Attention ici paragraphs va me donner par défaut 3 paragraphes mais dans un tableau mais le tableau ici ne va pas du tout aller dans la fonction setContent. setContent c'est une fonction qui attend une chaine de caractère. Donc si je veux que moi tableau soit vraiment propre, je dois faire ceci. ( plus haut ma variable $content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months')) // Permet de donner une date de début et une date de fin et il va nous filer une date au hasard entre les deux. | Ici, je précise au minimum il y a 6 mois et au max maintenant.

                        // Il me manque de dire à cet article dans quel catégorie il se place 
                        ->setCategory($category);

                $manager->persist($article); 

                // Il nous manque le fait de lui donner des commentaires.
                for($k = 1; $k <= mt_rand(4, 10); $k++)
                {
                    $comment = new Comment();

                    $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '<p>';

                    /* $now = new \DateTime(); */
                    
                    /* $interval = $now->diff($article->getCreatedAt()); */ // DateTime ::diff() <=> Récupère la différence entre deux objets DateTime ! | Représente la différence entre 2 dates, entre maintenant et la date de création de l'article. | Cet interval à toute sorte de propriété, par exemple un nbr de jour qui se sont passé depuis ce moment là.
                    /* $interval = $now->diff($article->getCreatedAt())->days; */
                    /* $days = $now->diff($article->getCreatedAt())->days; */
                    $days = ( new \DateTime())->diff($article->getCreatedAt())->days;
                    // Ici par exemple je peux créer une variable jour et qui serait égale à interval->days
                    /* $days = $interval->days; */ // Je sais ici encore une fois que Faker peut prendre une notation du genre -100 days. | $interval est devenu $days juste au dessus car j'ai raccourcis la ligne : $interval = $now->diff($article->getCreatedAt()); qui est devenu : $days = $now->diff($article->getCreatedAt())->days;
                    
                    //  Du coup je vais me créer une petite variable que je vais appeler minimum et qui serait égale à une chaine de caractère qui contient
                    /* $minimum = '-' . $days . 'days'; */ // -100 days | Et du cop je peux maintenant balancer ça à Faker
                    

                    // Et je commence à le nourir
                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            // Il faut faire attention à ce que la date du commentaire soit avant que la date de création de l'article.
                            /* ->setCreatedAt($faker->dateTimeBetween($minimum)); */ // Entre le minimum et maintenant.
                            ->setCreatedAt($faker->dateTimeBetween('-' . $days . 'days'))

                            // Je dis maintenant à quel article appartient ce commentaire.
                            ->setArticle($article);

                        $manager->persist($comment);
                }
            }
            
            
        }
        $manager->flush();
        /* for ($i = 1; $i <= 10; $i++) */    // Je crée 10 articles
       /*  {
            $article = new Article(); */ // Pour créer un article, je vais tout simplement instancier une classe $article qui sera égaleà new Article
                // Attention, dans Symfony dés qu'on utilise le nom d'une classe, il ne faut pas oublier de rajouter un use en haut (une sorte de require_once même si ce n'est pas vrmt ça) Pour expliqué à PHP d'où vient la classe Article. Elle vient de la classe App\Entity\Article; Pour être sur de son chemin je vais voir dans ma classe Article qui représente un article avec son titre son contenu etc dans le fichier Article.php du dossier Entity et je regarde où elle est (donc son namespace se trouve dans App\Entity et elle s'appel Article)
                // Maintenant, je peut commencer à écrire des choses dans mon article.
                /* $article->setTitle("Titre de l'article n°$i") */  // ASTUCE : Utilisez Faker ! <=> Utiliser la librairie Faker pour créer de fausse données !
                // Ce qui est cool avec les setters que Symfony m'a généré c'est que je peux les enchainer.
                /* ->setContent("<p>Contenu de l'article n°$i</p>")
                ->setImage("http://placehold.it/350x150")
                ->setCreatedAt(new \DateTime()); */ // Attention createdAt est une propriété de type datetime il faut donc que je lui passe un objet datetime de php en lui disant new \DateTime() | Pourquoi un \ ? Car les classes dans Symfony doient toute faire partie d'un espace de nom (un namespace). Ici par exemple ArticleFixture fait partie du namespace App\DataFixtures. Et ici, datetime, j'aimerais bien préciser que c'est une classe qui fait partie du namespace global de PHP. Donc je met un \ devant.

                // Attention le fait de créer un article ici ne veut pas dire qu'il existe dans la BDD. 
                // Pour ça, il faut faire 2 choses.

                //  Je dois demander à mon Manager de se préparer pour faire persister dans le temps mon article
                /* $manager->persist($article); */    // Le fait de faire persister c'est une préparation à le faire persister mais ce n'est pas le fait qu'il éxiste réellement dans la BDD.

        /* } */
        // Si je veux que mes 10 articles que je prépare à faire persister à chaque fois soit vraiment dans la BDD
        /* $manager->flush(); */  //  Donc je dois utiliser ceci <=> La fonction flush() <=> balance réellement la requête SQL qui mettra en place les différentes manipulation que j'ai fait ici.
    }
}   // php bin/console doctrine:fixtures :load <=> Charge toutes nos fixtures dans la base ! 
