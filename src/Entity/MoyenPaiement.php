<?php

namespace App\Entity;

use App\Repository\MoyenPaiementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoyenPaiementRepository::class)
 */
class MoyenPaiement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Medecin::class, mappedBy="lesMoyensPaiement")
     */
    private $lesMedecins;

    public function __construct()
    {
        $this->lesMedecins = new ArrayCollection();
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
     * @return Collection|Medecin[]
     */
    public function getLesMedecins(): Collection
    {
        return $this->lesMedecins;
    }

    public function addLesMedecin(Medecin $lesMedecin): self
    {
        if (!$this->lesMedecins->contains($lesMedecin)) {
            $this->lesMedecins[] = $lesMedecin;
            $lesMedecin->addLesMoyensPaiement($this);
        }

        return $this;
    }

    public function removeLesMedecin(Medecin $lesMedecin): self
    {
        if ($this->lesMedecins->removeElement($lesMedecin)) {
            $lesMedecin->removeLesMoyensPaiement($this);
        }

        return $this;
    }
}
