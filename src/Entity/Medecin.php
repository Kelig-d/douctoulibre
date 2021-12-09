<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\MedecinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MedecinRepository::class)
 */
class Medecin extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $carteVitale;

    /**
     * @ORM\OneToMany(targetEntity=RendezVous::class, mappedBy="leMedecin", orphanRemoval=true)
     */
    private $rendezVous;

    /**
     * @ORM\ManyToMany(targetEntity=MoyenPaiement::class, inversedBy="lesMedecins")
     */
    private $lesMoyensPaiement;

    /**
     * @ORM\ManyToOne(targetEntity=Specialite::class, inversedBy="lesMedecins")
     * @ORM\JoinColumn(nullable=false)
     */
    private $laSpecialite;

    /**
     * @ORM\OneToMany(targetEntity=Horaires::class, mappedBy="leMedecin")
     * @ORM\JoinColumn(nullable=true)
     */
    private $lesHoraires;

    public function __construct()
    {
        $this->rendezVous = new ArrayCollection();
        $this->lesMoyensPaiement = new ArrayCollection();
        $this->lesHoraires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCarteVitale(): ?bool
    {
        return $this->carteVitale;
    }

    public function setCarteVitale(bool $carteVitale): self
    {
        $this->carteVitale = $carteVitale;

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
            $rendezvous->setLeMedecin($this);
        }

        return $this;
    }

    public function removeRendezVous(RendezVous $rendezvous): self
    {
        if ($this->rendezVous->removeElement($rendezvous)) {
            // set the owning side to null (unless already changed)
            if ($rendezvous->getLeMedecin() === $this) {
                $rendezvous->setLeMedecin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MoyenPaiement[]
     */
    public function getLesMoyensPaiement(): Collection
    {
        return $this->lesMoyensPaiement;
    }

    public function addLesMoyensPaiement(MoyenPaiement $lesMoyensPaiement): self
    {
        if (!$this->lesMoyensPaiement->contains($lesMoyensPaiement)) {
            $this->lesMoyensPaiement[] = $lesMoyensPaiement;
        }

        return $this;
    }

    public function removeLesMoyensPaiement(MoyenPaiement $lesMoyensPaiement): self
    {
        $this->lesMoyensPaiement->removeElement($lesMoyensPaiement);

        return $this;
    }

    public function getLaSpecialite(): ?Specialite
    {
        return $this->laSpecialite;
    }

    public function setLaSpecialite(?Specialite $laSpecialite): self
    {
        $this->laSpecialite = $laSpecialite;

        return $this;
    }

    /**
     * @return Collection|Horaires[]
     */
    public function getLesHoraires(): Collection
    {
        return $this->lesHoraires;
    }

    public function addLesHoraire(Horaires $lesHoraire): self
    {
        if (!$this->lesHoraires->contains($lesHoraire)) {
            $this->lesHoraires[] = $lesHoraire;
            $lesHoraire->setLeMedecin($this);
        }

        return $this;
    }

    public function removeLesHoraire(Horaires $lesHoraire): self
    {
        if ($this->lesHoraires->removeElement($lesHoraire)) {
            // set the owning side to null (unless already changed)
            if ($lesHoraire->getLeMedecin() === $this) {
                $lesHoraire->setLeMedecin(null);
            }
        }

        return $this;
    }

}
