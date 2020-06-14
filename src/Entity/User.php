<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
Use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface // UserInterface <=> L’interface qu’on doit implémenter si on veut créer des users
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire minimum 8 caractères")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas tappez le même mot de passe !!") 
     */
    public $confirm_password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    public function eraseCredentials(){}    // Ne fera rien

    public function getSalt(){}  // Ne fera rien aussi pour l'instant vu qu'on a rien de spécial.

    public function getRoles(){   // Doit renvoyer un tableau de chaine de caractère qui explique quel est le rôle de cet utilisateur. Pour l'instant vu qu'on a pas encore fait de gestion de rôles on va simplement dire que c'est un tableau qui va contenir un utilisateur classic. Il n'y a pas encore d'administration etc donc c'est juste un role_user. A terme ici, quand on aura plusieurs rôles il faudra ici que getRoles envoie les rôles qui sont au sein de cet utilisateur, qui sont donné à cet utilisateur.
        
    return ['ROLE_USER'];
    }

}
