<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $montantTransction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clientRecepteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clientEmetteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telRecepteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telEmetteur;

    /**
     * @ORM\Column(type="integer")
     */
    private $frais;

    /**
     * @ORM\Column(type="float")
     */
    private $partEmetteur;

    /**
     * @ORM\Column(type="float")
     */
    private $partRecepteur;

    /**
     * @ORM\Column(type="float")
     */
    private $partSysteme;

    /**
     * @ORM\Column(type="float")
     */
    private $partEtat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $compteEnvoi;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $compteRetrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userEnvoi;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userRetrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cniEmetteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cniRecepteur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantTransction(): ?float
    {
        return $this->montantTransction;
    }

    public function setMontantTransction(float $montantTransction): self
    {
        $this->montantTransction = $montantTransction;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getClientEmetteur(): ?string
    {
        return $this->clientEmetteur;
    }

    public function setClientEmetteur(string $clientEmetteur): self
    {
        $this->clientEmetteur = $clientEmetteur;

        return $this;
    }

    public function getClientRecepteur(): ?string
    {
        return $this->clientRecepteur;
    }

    public function setClientRecepteur(string $clientRecepteur): self
    {
        $this->clientRecepteur = $clientRecepteur;

        return $this;
    }

    public function getTelRecepteur(): ?string
    {
        return $this->telRecepteur;
    }

    public function setTelRecepteur(string $telRecepteur): self
    {
        $this->telRecepteur = $telRecepteur;

        return $this;
    }

    public function getFrais(): ?int
    {
        return $this->frais;
    }

    public function setFrais(int $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getPartEmetteur(): ?float
    {
        return $this->partEmetteur;
    }

    public function setPartEmetteur(float $partEmetteur): self
    {
        $this->partEmetteur = $partEmetteur;

        return $this;
    }

    public function getPartRecepteur(): ?float
    {
        return $this->partRecepteur;
    }

    public function setPartRecepteur(float $partRecepteur): self
    {
        $this->partRecepteur = $partRecepteur;

        return $this;
    }

    public function getPartSysteme(): ?float
    {
        return $this->partSysteme;
    }

    public function setPartSysteme(float $partSysteme): self
    {
        $this->partSysteme = $partSysteme;

        return $this;
    }

    public function getPartEtat(): ?float
    {
        return $this->partEtat;
    }

    public function setPartEtat(float $partEtat): self
    {
        $this->partEtat = $partEtat;

        return $this;
    }

    public function getCompteEnvoi(): ?Compte
    {
        return $this->compteEnvoi;
    }

    public function setCompteEnvoi(?Compte $compteEnvoi): self
    {
        $this->compteEnvoi = $compteEnvoi;

        return $this;
    }

    public function getCompteRetrait(): ?Compte
    {
        return $this->compteRetrait;
    }

    public function setCompteRetrait(?Compte $compteRetrait): self
    {
        $this->compteRetrait = $compteRetrait;

        return $this;
    }

    public function getUserEnvoi(): ?User
    {
        return $this->userEnvoi;
    }

    public function setUserEnvoi(?User $userEnvoi): self
    {
        $this->userEnvoi = $userEnvoi;

        return $this;
    }

    public function getUserRetrait(): ?User
    {
        return $this->userRetrait;
    }

    public function setUserRetrait(?User $userRetrait): self
    {
        $this->userRetrait = $userRetrait;

        return $this;
    }

    public function getCniRecepteur(): ?string
    {
        return $this->cniRecepteur;
    }

    public function setCniRecepteur(string $cniRecepteur): self
    {
        $this->cniRecepteur = $cniRecepteur;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}
