<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
Use Symfony\Compenent\HttpFoundation\Request ;
Use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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


    /**
     * @Route("/blog/new", name="blog_create")
     */
    public function create(/* Request $request, objectManager $manager */)
    {
        $article = new Article(); // C'est un article vide prêt à être rempli. Maintenant je veux créer un formulaire, je vais utiliser la méthod createFormBuilder et je dois lui passer une entité qui m'interesse.

        $form = $this->createFormBuilder($article) // ça va me créer un form qui est lié à mon article. Cependant, il n'est pas configuré, il ne réprésente donc rien. Je dois lui donner maintenant les champs que je veux traiter dans ce formulaire. Je vais donc utiliser la fonction add() qui permet d'ajouter des champs à ce formulaire.

            ->add('title', TextType::class, // Dans la plupart des cas, on fait confiance à Symfony mais on peut toujours si on le souhaite, le configurer à notre guise.
            [
                'attr' => [ // attr => On peut encore donner un dernier paramètre à cette fonction add pour encore plus configurer notre champ. Et ce dernier paramètre représente les options de notre champ. | les options des attributs. j’ai peut-être envie de donner une classe css / un identifiant / placeholder / etc 
                    'placeholder' => "Titre de l'article"
                ]
            ]) 
            ->add('content', TextareaType::class, // Ne pas oublier le use pour le TextType & TextareaType pour expliquer à PHP d'où vient le textType
            [
                'attr' => [
                    'placeholder' => "Contenu de l'article"
                ]
            ])  
            ->add('image', TextType::class,
            [
                'attr' =>
                [
                    'placeholder' => "Image de l'article"
                ]
            ])
            /* ->add('save', SubmitType::class,
            [
                'label' => 'Enregistrer'
            ]) */
            // Une fois que j'ai fini de configurer mon formulaire, j'ai envie d'avoir le résultat final qui est la fonction getForm()
            ->getForm(); // Donc on demande à créer un formBuilder, on le configure et à la fin on lui dit, ok, maintenant file moi le form que je t'ai demandé de construire.

            // La 1ere chose que je veux faire, c'est afficher ce formulaire. Donc je veux passer ce formulaire à twig. Je vais donc lui passer une variable qui soit relativement facile à afficher.

        // Je passe donc à twig, un tableau qui contiendra les différentes informations que j'ai envie de lui passer et je vais lui passer par exemple une variable qui s'appelle form et qui ne contient pas $form (car c'est un objet qui est complexe et qui a beaucoup de méthode, beaucoup de chose, ce n'est pas ce que twig veut avoir entre les mains). Il veut avoir le résultat de la fonction createView() de ce formulaire. Car cette grosse classe avec toutes ces méthodes possède notamment une méthode createView() qui va créer un petit objet qui représente pour le coup, plus l'aspect affichage de notre formulaire et c'est ça qu'on va passer à twig.
        return $this->render('blog/create.html.twig', 
    [
        // Pour éviter toute confusion, je donne le nom formArticle à mon formulaire.
        'formArticle' => $form->createView()   // Ce que je peux faire désormais c'est faire afficher ce formulaire à twig / dans create.html.twig
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