<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 * fields = {"mail"},
 * message = "Email déjà utilisé, veuillez taper un autre email !")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *      min = 4,
     *      max = 50,
     *      minMessage = "Your Username name must be at least {{ limit }} characters long",
     *      maxMessage = "Your Username name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Email(
     *      message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 4,
     *      max = 50,
     *      minMessage = "Your Password must be at least {{ limit }} characters long",
     *      maxMessage = "Your Password cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private $password;
    /**
     * @Assert\EqualTo(propertyPath="password") 
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Trick::class, mappedBy="user", orphanRemoval=true)
     */
    private $tricks;

    /**
     * @ORM\OneToMany(targetEntity=Trick::class, mappedBy="user", orphanRemoval=true)
     */
    private $user;

    public function __construct()
    {
        $this->trick = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Trick[]
     */
    public function getTricks(): Collection
    {
        return $this->tricks;
    }

    public function addTrick(Trick $trick): self
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
            $trick->setUser($this);
        }

        return $this;
    }

    public function removeTrick(Trick $trick): self
    {
        if ($this->tricks->contains($trick)) {
            $this->tricks->removeElement($trick);
            // set the owning side to null (unless already changed)
            if ($trick->getUser() === $this) {
                $trick->setUser(null);
            }
        }

        return $this;
    }

    public function eraseCredentials(){} //fonction vide obligatoire pour l'implémentation 

    public function getSalt(){} // idem

    public function getUsername()
    {
        return $this->mail;
    }

    public function getRoles(): array 
    {
       //$roles = $this->roles;
       //$roles[] = 'ROLE_USER'; //par défaut chaque user à un ROLE_USER
       //return array_unique($roles);
       return ['ROLE_USER'];
    }

   /* public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }*/

   
}
