<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"read"}},
 *      denormalizationContext={"groups"={"write"}},
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ContratRepository")
 */
class Contrat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateSignature;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Partenaire", inversedBy="contrat", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $partenaire;

    public function __construct(\DateTimeInterface $dateSignature)
    {
        $description = "Le vice, selon l’article 1641 c.civ., s’entend d’un « défaut » de la chose. 
                        Le vice est donc « nécessairement inhérent à la chose elle-même » et ne saurait découler de facteurs extrinsèques – ainsi, 
                        un médicament n’est pas affecté d’un risque à raison de son incompatibilité avec un autre (Civ. 1, 8 avr. 1986, n° 84-11.443, Bull. 
                        civ. I, 82) –, quoique la jurisprudence admette que des éléments liés à la chose contiennent le vice de celle-ci – ainsi, 
                        la fragilité du sol sur lequel est bâti un immeuble peut constituer le vice (Civ. 3, 24 janv. 2012, n° 11-10.420).";
                        
        $this->dateSignature = new \DateTime();
        $this->description = $description;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateSignature(): ?\DateTimeInterface
    {
        return $this->dateSignature;
    }

    public function setDateSignature(\DateTimeInterface $dateSignature): self
    {
        $this->dateSignature = $dateSignature;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(Partenaire $partenaire): self
    {
        $this->partenaire = $partenaire;

        return $this;
    }
}
