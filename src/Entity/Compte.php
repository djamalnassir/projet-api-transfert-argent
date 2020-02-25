<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\CompteController;

/**
 * @ApiResource(
 * 
 *      normalizationContext={"groups"={"read"}},
 *      denormalizationContext={"groups"={"write"}},
 * 
 *      normalizationContext={"groups"={"input"}},
 *      denormalizationContext={"groups"={"output"}},
 * 
 *      collectionOperations={
 *          "get"={
 *              "access_control"="is_granted('ROLE_ADMIN')" 
 *          },
 *          "post"={
 *              "controller"=CompteController::class,
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
 * @ORM\Entity(repositoryClass="App\Repository\CompteRepository")
 */
class Compte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"output", "input"})
     * @ORM\Column(type="string", length=255)
     */
    private $numeroCompte;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="float")
     */
    private $soldeCompte;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comptes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $userCreateur;

    /**
     * @Groups({"read", "write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="comptes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $partenaire;

    /**
     * @Groups({"read", "write"})
     * @ORM\OneToMany(targetEntity="App\Entity\Depot", mappedBy="compte", orphanRemoval=true, cascade={"persist"})
     */
    private $depots;

    public function __construct()
    {
        $this->soldeCompte = 0;
        $this->depots = new ArrayCollection();
        $this->dateCreation = new \DateTime();
        $this->numeroCompte = "C00001A";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroCompte(): ?string
    {
        return $this->numeroCompte;
    }

    public function setNumeroCompte(string $numeroCompte): self
    {
        $this->numeroCompte = $numeroCompte;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getUserCreateur(): ?User
    {
        return $this->userCreateur;
    }

    public function setUserCreateur(?User $userCreateur): self
    {
        $this->userCreateur = $userCreateur;

        return $this;
    }

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(?Partenaire $partenaire): self
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    public function getSoldeCompte(): ?float
    {
        return $this->soldeCompte;
    }

    public function setSoldeCompte(float $soldeCompte): self
    {
        $this->soldeCompte = $soldeCompte;

        return $this;
    }

    /**
     * @return Collection|Depot[]
     */
    public function getDepots(): Collection
    {
        return $this->depots;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depots->contains($depot)) {
            $this->depots[] = $depot;
            $depot->setCompte($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->contains($depot)) {
            $this->depots->removeElement($depot);
            // set the owning side to null (unless already changed)
            if ($depot->getCompte() === $this) {
                $depot->setCompte(null);
            }
        }

        return $this;
    }
}
