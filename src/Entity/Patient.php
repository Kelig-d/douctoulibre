<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PatientRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=PatientRepository::class)
 */
class Patient extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nomNaissance;

    /**
     * @ORM\OneToMany(targetEntity=RendezVous::class, mappedBy="lePatient", orphanRemoval=true)
     */
    private $rendezVous;


    public function __construct()
    {
        $this->rendezVous = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getNomNaissance(): ?string
    {
        return $this->nomNaissance;
    }

    public function setNomNaissance(?string $nomNaissance): self
    {
        $this->nomNaissance = $nomNaissance;

        return $this;
    }

    /**
     * @return Collection|RendezVous[]
     */
    public function getRendezVous(): Collection
    {
        return $this->rendezVous;
    }

    public function addRendezVous(RendezVous $rendezvous): self
    {
        if (!$this->rendezVous->contains($rendezvous)) {
            $this->rendezVous[] = $rendezvous;
            $rendezvous->setLePatient($this);
        }

        return $this;
    }

    public function removeRendezVous(RendezVous $rendezvous): self
    {
        if ($this->rendezVous->removeElement($rendezvous)) {
            // set the owning side to null (unless already changed)
            if ($rendezvous->getLePatient() === $this) {
                $rendezvous->setLePatient(null);
            }
        }

        return $this;
    }
}
