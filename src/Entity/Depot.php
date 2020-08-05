<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\DepotController;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * 
 *      normalizationContext={"groups"={"compteRead"}},
 *      denormalizationContext={"groups"={"compteWrite"}},
 * 
 *      normalizationContext={"groups"={"depotRead"}},
 *      denormalizationContext={"groups"={"depotWrite"}},
 * 
 *      collectionOperations={
 *          "get"={
 *              "access_control"="is_granted('ROLE_ADMIN')" 
 *          },
 *          "post"={
 *              "controller"=DepotController::class,
 *              "access_control"="is_granted('POST', object)",
 *              "access_control_message"="Vous avez un profile qui n'est pas abilité à faire cette action"
 *          },
 *      },
 * 
 *     itemOperations={
 *          "get"={
 *              "access_control"="is_granted('GET', object)",
 *              "access_control_message"="Vous avez un profile qui n'est pas abilité à faire cette action"
 *          },
 *          "put"={
 *              "access_control"="is_granted('EDIT', object)",
 *              "access_control_message"="Vous avez un profile qui n'est pas abilité à faire cette action"
 *          },
 *          "delete"={
 *              "access_control"="is_granted('DELET', object)",
 *              "access_control_message"="Vous avez un profile qui n'est pas abilité à faire cette action"
 *          }
 *     },
 * )
 * @ORM\Entity(repositoryClass="App\Repository\DepotRepository")
 */
class Depot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 
     * @ORM\Column(type="date")
     */
    private $dateDepot;

    /**
     * @Groups({"compteRead", "compteWrite"})
     * @Groups({"depotRead", "depotWrite"})
     * @ORM\Column(type="float")
     */
    private $montantDepot;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="depots", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @Groups({"depotRead", "depotWrite"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="depots", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $compte;


    public function __construct()
    {
        $this->dateDepot = new \DateTime();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(\DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getMontantDepot(): ?float
    {
        return $this->montantDepot;
    }

    public function setMontantDepot(float $montantDepot): self
    {
        $this->montantDepot = $montantDepot;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
}
