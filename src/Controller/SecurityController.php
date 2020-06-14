<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface; // A la place de ObjectManager car ne fonctionnait pas.
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) 
                                    // La fonctionne registration à besoin de : 
                                    // La requête HTTP => pour pouvoir l'analyser, pour que notre formulaire en tire les informations qui sont ressorti.
                                    // L'objectManager de doctrine => qui va permettre d'enregistrer l'utilisateur en BDD.
    {                               // UserPasswordEncoder => Interface qui permet d’encoder les mots de passes !
        // Dire à quel Objet relier les champs du formulaire.
        $user = new User();

        // J'instancie mon formulaire. | $user => je relie les champs du formulaire au champs de l'utilisateurs.
        $form = $this->createForm(RegistrationType::class, $user);

        // Je veux que le formulaire analyse la request que je te passe ici qui s'appelle $request
        $form->handleRequest($request);

        // Si le formulaire est soumis et que tout ses champs sont valide
        if($form->isSubmitted() && $form->isValid() )
        {
            // Avant de sauvegarder mon utilisateur, j'ai envie de dire : je veux ici un hash et ça sera égale à mon encodeur à qui je vais demander d'encoder un password encodePassword()
            // Mais la cet encodeur peut encoder en pleins d'algorithme différent. Je dois donc lui faire comprendre quel est l'algorithme qui m'intéresse. Je lui passe donc mon $user car $user est une instance de la classe EntityUser et dans le security.yaml j'ai dit quand on tombe sur User il faut utiliser l'algorithme bcrypt
            $hash = $encoder->encodePassword($user, $user->getPassword()); // Je lui donne le mdp que je veux encoder. Il est dans $user->getPassword() | J'ai du coup dans mon hash, le bon hash.

            // J'ai mon mdp encoder dans $hash donc je peux dire : Je modifie ton pswd et à la place je te met le hash.
            $user->setPassword($hash);

            // Alors je veux que tu fasse persister dans le temps le $user dans la BDD.
            $manager->persist($user);
            $manager->flush();
        }

        // Je veux afficher ce fichier là et je lui passe des variable qu'il puisse utiliser 
        return $this->render('security/registration.html.twig',
        [
            // Je demande à mon formulaire de créer la vue.
            'form' => $form->createView()
        ]);
    }
}
