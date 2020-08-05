<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"profileRead"}},
 *     denormalizationContext={"groups"={"profileWrite"}},
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ProfileRepository")
 */
class Profile
{
    /**
     * @Groups({"profileRead", "profileWrite"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"profileRead", "profileWrite"})
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * 
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="profile", cascade={"persist"})
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfile($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getProfile() === $this) {
                $user->setProfile(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->libelle;
    }
}

/**
 * Titre: Normalisation et Denormalisation sur ApiPlatform
 * 
 * Sous-titre : Développement API avec Symfony4 & ApiPlatform 
 * 
 * Salut la communauté, j'espère que vous allez bien. 
 * Je suis à la quête d'aide pour un problème que je rencontre ces deux jours. 
 * Bref aperçu de mon projet, je travaille sur une API avec Symfony4 & ApiPlatfom. 
 * Je dispose de deux tables: User(username, password, objet:Profile) et Profile(libelle) lier en ManyToOne. 
 * Alors j'ai créé une normalisation et denormalisation personnalisées dans la table User 
 * dans le but de restreindre l'affichage de certains attributs à l'ajout d'un User comme suite
 * 
 * Mais le soucis que j'ai c'est que le profile ne doit pas venir comme tel mais plutôt sous forme d'IRI, 
 * c'est à dire que ça doit m'indiquer 
 * 
 * et là je renseigne l'id du profile à attribuer au User qu'on crée en faisant comme suite exemple:
 */
