<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SpecialiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpecialiteRepository::class)
 */
#[ApiResource]
class Specialite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Medecin::class, mappedBy="laSpecialite")
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
            $lesMedecin->setLaSpecialite($this);
        }

        return $this;
    }

    public function removeLesMedecin(Medecin $lesMedecin): self
    {
        if ($this->lesMedecins->removeElement($lesMedecin)) {
            // set the owning side to null (unless already changed)
            if ($lesMedecin->getLaSpecialite() === $this) {
                $lesMedecin->setLaSpecialite(null);
            }
        }

        return $this;
    }
}
