<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface; // A la place de ObjectManager car ne fonctionnait pas.
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager) // La fonctionne registration à besoin de : 
                                    // La requête HTTP => pour pouvoir l'analyser, pour que notre formulaire en tire les informations qui sont ressorti.
                                    // L'objectManager de doctrine => qui va permettre d'enregistrer l'utilisateur en BDD.
    {
        // Dire à quel Objet relier les champs du formulaire.
        $user = new User();

        // J'instancie mon formulaire. | $user => je relie les champs du formulaire au champs de l'utilisateurs.
        $form = $this->createForm(RegistrationType::class, $user);

        // Je veux que le formulaire analyse la request que je te passe ici qui s'appelle $request
        $form->handleRequest($request);

        // Si le formulaire est soumis et que tout ses champs sont valide
        if($form->isSubmitted() && $form->isValid() )
        {
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
