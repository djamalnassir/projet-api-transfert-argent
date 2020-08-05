<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\GetUsers;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ApiResource(
 * 
 *      normalizationContext={"groups"={"userRead"}},
 *      denormalizationContext={"groups"={"userWrite"}},
 * 
 *      collectionOperations={
 * 
 *          "post"={
 *              
 *              "access_control"="is_granted('POST', object)",
 *              "access_control_message"="Vous n'êtes pas autorisé à effectuer cette action"
 *           }
 *      },
 * 
 *      itemOperations={
 *          "get"={
 *              "access_control"="is_granted('GET', object)",
 *              "access_control_message"="Vous n'êtes pas autorisé à effectuer cette action"
 *          },
 *          "put"={
 *              "access_control"="is_granted('EDIT', object)",
 *              "access_control_message"="Vous n'êtes pas autorisé à effectuer cette action"
 *          },
 * 
 *          "delete"={
 *              "access_control"="is_granted('DELET', object)",
 *              "access_control_message"="Vous n'êtes pas autorisé à effectuer cette action"
 *          }
 *     },
 * )
 * 
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @Groups({"userRead", "userWrite"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"userRead", "userWrite"})
     * @Groups({"compteRead", "compteWrite"})
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @Groups("userRead")
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @Groups({"userRead", "userWrite"})
     * @Groups({"compteRead", "compteWrite"})
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @Groups({"userRead", "userWrite"})
     * @Groups({"compteRead", "compteWrite"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Profile", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $profile;

    private $role = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="users")
     */
    private $partenaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compte", mappedBy="user")
     */
    private $comptes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Depot", mappedBy="user")
     */
    private $depots;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="userEnvoi")
     */
    private $transactionEnvois;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="userRetrait")
     */
    private $transactionRetraits;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="userAffecte")
     */
    private $affectationComptes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="userAffecteur")
     */
    private $affectationUsers;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    public function __construct()
    {
        $this->isActive = true;
        $this->comptes = new ArrayCollection();
        $this->depots = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->affectations = new ArrayCollection();
        /*$role = 'ROLE_' . strtoupper($this->getProfile()->getLibelle());
        return $this->roles[] = $role;*/
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getRoles(): ?array
    {
        $generate_role = 'ROLE_' . strtoupper($this->getProfile()->getLibelle());
        return $this->role = array($generate_role);
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user
     */
    public function eraseCredentials()
    {
        return null;
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

    /**
     * @return Collection|Compte[]
     */
    public function getComptes(): Collection
    {
        return $this->comptes;
    }

    public function addCompte(Compte $compte): self
    {
        if (!$this->comptes->contains($compte)) {
            $this->comptes[] = $compte;
            $compte->setUser($this);
        }

        return $this;
    }

    public function removeCompte(Compte $compte): self
    {
        if ($this->comptes->contains($compte)) {
            $this->comptes->removeElement($compte);
            // set the owning side to null (unless already changed)
            if ($compte->getUser() === $this) {
                $compte->setUser(null);
            }
        }

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
            $depot->setUser($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->contains($depot)) {
            $this->depots->removeElement($depot);
            // set the owning side to null (unless already changed)
            if ($depot->getUser() === $this) {
                $depot->setUser(null);
            }
        }

        return $this;
    }

    // Opération d'envoi
    /**
     * @return Collection|Transaction[]
     */
    public function getTransactionEnvois(): Collection
    {
        return $this->transactionEnvois;
    }

    public function addTransactionEnvoi(Transaction $transaction): self
    {
        if (!$this->transactionEnvois->contains($transaction)) {
            $this->transactionEnvois[] = $transaction;
            $transaction->setUserEnvoi($this);
        }

        return $this;
    }

    public function removeTransactionEnvoi(Transaction $transaction): self
    {
        if ($this->transactionEnvois->contains($transaction)) {
            $this->transactionEnvois->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getUserEnvoi() === $this) {
                $transaction->setUserEnvoi(null);
            }
        }

        return $this;
    }

    // Opération de retrait
    /**
     * @return Collection|Transaction[]
     */
    public function getTransactionRetraits(): Collection
    {
        return $this->transactionRetraits;
    }

    public function addTransactionRetrait(Transaction $transaction): self
    {
        if (!$this->transactionRetraits->contains($transaction)) {
            $this->transactionRetraits[] = $transaction;
            $transaction->setUserRetrait($this);
        }

        return $this;
    }

    public function removeTransactionRetrait(Transaction $transaction): self
    {
        if ($this->transactionRetraits->contains($transaction)) {
            $this->transactionRetraits->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getUserRetrait() === $this) {
                $transaction->setUserRetrait(null);
            }
        }

        return $this;
    }

    // Opération d'affectation d'un USER à compte
    /**
     * @return Collection|Affectation[]
     */
    public function getAffectationComptes(): Collection
    {
        return $this->affectationUsers;
    }

    public function addAffectationCompte(Affectation $affectation): self
    {
        if (!$this->affectationComptes->contains($affectation)) {
            $this->affectationComptes[] = $affectation;
            $affectation->setUserAffecte($this);
        }

        return $this;
    }

    public function removeAffectationCompte(Affectation $affectation): self
    {
        if ($this->affectationComptes->contains($affectation)) {
            $this->affectationComptes->removeElement($affectation);
            // set the owning side to null (unless already changed)
            if ($affectation->getUserAffecte() === $this) {
                $affectation->setUserAffecte(null);
            }
        }

        return $this;
    }

    // Opération d'affectation d'un USER à un compte
    /**
     * @return Collection|Affectation[]
     */
    public function getAffectationUsers(): Collection
    {
        return $this->affectations;
    }

    public function addAffectationUser(Affectation $affectation): self
    {
        if (!$this->affectationUsers->contains($affectation)) {
            $this->affectationUsers[] = $affectation;
            $affectation->setUserAffecteur($this);
        }

        return $this;
    }

    public function removeAffectationUser(Affectation $affectation): self
    {
        if ($this->affectationUsers->contains($affectation)) {
            $this->affectationUsers->removeElement($affectation);
            // set the owning side to null (unless already changed)
            if ($affectation->getUserAffecteur() === $this) {
                $affectation->setUserAffecteur(null);
            }
        }

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

}
