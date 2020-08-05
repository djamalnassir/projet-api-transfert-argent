<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\CompteController;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 * 
 *      normalizationContext={"groups"={"compteRead"}},
 *      denormalizationContext={"groups"={"compteWrite"}},
 * 
 *      collectionOperations={
 * 
 *          "get"={},
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
 * @ApiFilter(SearchFilter::class, properties={"partenaire": "exact"})
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
     * 
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
     * @Groups({"compteRead", "compteWrite"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="comptes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $partenaire;

    /**
     * 
     * @Groups({"compteRead", "compteWrite"})
     * @ORM\OneToMany(targetEntity="App\Entity\Depot", mappedBy="compte", orphanRemoval=true, cascade={"persist"})
     */
    private $depots;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="compteEnvoi", orphanRemoval=true)
     */
    private $transactionEnvois;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="compteRetrait", orphanRemoval=true)
     */
    private $transactionRetraits;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="compte", orphanRemoval=true)
     */
    private $affectations;

    public function __construct()
    {
        $this->soldeCompte = 0;
        $this->depots = new ArrayCollection();
        $this->dateCreation = new \DateTime();
        $this->numeroCompte = "C00001A";
        $this->transactions = new ArrayCollection();
        $this->affectations = new ArrayCollection();
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

    // Opération d'envoi d'argent
    /**
     * @return Collection|Transaction[]
     */
    public function getTransactionEnvois(): Collection
    {
        return $this->transactions;
    }

    public function addTransactionEnvoi(Transaction $transaction): self
    {
        if (!$this->transactionEnvois->contains($transaction)) {
            $this->transactionEnvois[] = $transaction;
            $transaction->setCompteEnvoi($this);
        }

        return $this;
    }

    public function removeTransactionEnvoi(Transaction $transaction): self
    {
        if ($this->transactionEnvois->contains($transaction)) {
            $this->transactionEnvois->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getCompteEnvoi() === $this) {
                $transaction->setCompteEnvoi(null);
            }
        }

        return $this;
    }


    // Opération de retrait d'argent
    /**
     * @return Collection|Transaction[]
     */
    public function getTransactionRetraits(): Collection
    {
        return $this->transactions;
    }

    public function addTransactionRetrait(Transaction $transaction): self
    {
        if (!$this->transactionRetraits->contains($transaction)) {
            $this->transactionRetraits[] = $transaction;
            $transaction->setCompteRetrait($this);
        }

        return $this;
    }

    public function removeTransactionRetrait(Transaction $transaction): self
    {
        if ($this->transactionRetraits->contains($transaction)) {
            $this->transactionRetraits->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getCompteRetrait() === $this) {
                $transaction->setCompteRetrait(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Affectation[]
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): self
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations[] = $affectation;
            $affectation->setCompte($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): self
    {
        if ($this->affectations->contains($affectation)) {
            $this->affectations->removeElement($affectation);
            // set the owning side to null (unless already changed)
            if ($affectation->getCompte() === $this) {
                $affectation->setCompte(null);
            }
        }

        return $this;
    }
}
