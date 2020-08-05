<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\AffectationController;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * 
 *      normalizationContext={"groups"={"affectationRead"}},
 *      denormalizationContext={"groups"={"affectationWrite"}},
 *      
 *      collectionOperations={
 * 
 *          "post"={
 *              "controller"=AffectationController::class,
 *              "access_control"="is_granted('POST', object)",
 *              "access_control_message"="Vous avez un profile n'est pas abilité"
 *          },
 *      },
 * 
 *      itemOperations={
 *          "get"={
 *              "access_control"="is_granted('GET', object)",
 *              "access_control_message"="Vous avez un profile n'est pas abilité"
 *          },
 *          "put"={
 *              "access_control"="is_granted('EDIT', object)",
 *              "access_control_message"="Vous avez un profile n'est pas abilité"
 *          },
 *          "delete"={
 *              "access_control"="is_granted('DELET', object)"
 *          }
 *     },
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AffectationRepository")
 */
class Affectation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"affectationRead", "affectationWrite"})
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @Groups({"affectationRead", "affectationWrite"})
     * @ORM\Column(type="date")
     */
    private $dateFin;

    /**
     * @Groups({"affectationRead", "affectationWrite"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="affectations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $compte;

    // Il s'agit du USER à qui on affecte un compte
    /**
     * @Groups({"affectationRead", "affectationWrite"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="affectationComptes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userAffecte;

    // Ce USER effectue l'affectation
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="affectationUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userAffecteur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getUserAffecte(): ?User
    {
        return $this->userAffecte;
    }

    public function setUserAffecte(?User $userAffecte): self
    {
        $this->userAffecte = $userAffecte;

        return $this;
    }

    public function getUserAffecteur(): ?User
    {
        return $this->userAffecteur;
    }

    public function setUserAffecteur(?User $userAffecteur): self
    {
        $this->userAffecteur = $userAffecteur;

        return $this;
    }
    // ajv12345AJV
}
