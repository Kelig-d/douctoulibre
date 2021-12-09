<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RendezVousRepository::class)
 */
class RendezVous
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree;

    /**
     * @ORM\ManyToOne(targetEntity=Medecin::class, inversedBy="rendezVous")
     * @ORM\JoinColumn(nullable=false)
     */
    private $leMedecin;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="rendezVous")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lePatient;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getLeMedecin(): ?Medecin
    {
        return $this->leMedecin;
    }

    public function setLeMedecin(?Medecin $leMedecin): self
    {
        $this->leMedecin = $leMedecin;

        return $this;
    }

    public function getLePatient(): ?Patient
    {
        return $this->lePatient;
    }

    public function setLePatient(?Patient $lePatient): self
    {
        $this->lePatient = $lePatient;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
