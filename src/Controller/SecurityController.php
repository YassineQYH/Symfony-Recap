<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration()
    {
        // Dire à quel Objet relier les champs du formulaire.
        $user = new User();

        // J'instancie mon formulaire. | $user => je relie les champs du formulaire au champs de l'utilisateurs.
        $form = $this->createForm(RegistrationType::class, $user);

        // Je veux afficher ce fichier là et je lui passe des variable qu'il puisse utiliser 
        return $this->render('security/registration.html.twig',
        [
            // Je demande à mon formulaire de créer la vue.
            'form' => $form->createView()
        ]);
    }
}
