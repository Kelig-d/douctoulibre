<?php

namespace App\Entity;

use App\Repository\HorairesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HorairesRepository::class)
 */
class Horaires
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $heureMatinDebut;

    /**
     * @ORM\Column(type="time")
     */
    private $heureMatinFin;

    /**
     * @ORM\Column(type="time")
     */
    private $heureApremDebut;

    /**
     * @ORM\Column(type="time")
     */
    private $heureApremFin;

    /**
     * @ORM\ManyToOne(targetEntity=Medecin::class, inversedBy="lesHoraires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $leMedecin;

    /**
     * @ORM\ManyToOne(targetEntity=Jour::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $leJour;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeureMatinDebut(): ?\DateTimeInterface
    {
        return $this->heureMatinDebut;
    }

    public function setHeureMatinDebut(\DateTimeInterface $heureMatinDebut): self
    {
        $this->heureMatinDebut = $heureMatinDebut;

        return $this;
    }

    public function getHeureMatinFin(): ?\DateTimeInterface
    {
        return $this->heureMatinFin;
    }

    public function setHeureMatinFin(\DateTimeInterface $heureMatinFin): self
    {
        $this->heureMatinFin = $heureMatinFin;

        return $this;
    }

    public function getHeureApremDebut(): ?\DateTimeInterface
    {
        return $this->heureApremDebut;
    }

    public function setHeureApremDebut(\DateTimeInterface $heureApremDebut): self
    {
        $this->heureApremDebut = $heureApremDebut;

        return $this;
    }

    public function getHeureApremFin(): ?\DateTimeInterface
    {
        return $this->heureApremFin;
    }

    public function setHeureApremFin(\DateTimeInterface $heureApremFin): self
    {
        $this->heureApremFin = $heureApremFin;

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

    public function getLeJour(): ?Jour
    {
        return $this->leJour;
    }

    public function setLeJour(?Jour $leJour): self
    {
        $this->leJour = $leJour;

        return $this;
    }
}
