<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IntervenantRepository")
 */
class Intervenant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $specialiteprofessionnelle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Matiere", mappedBy="fk_intervenant")
     */
    private $matieres;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cours", mappedBy="fk_intervenant_id")
     */
    private $cours;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="fk_intervenant_id")
     */
    private $notifications;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Disponibilite", mappedBy="fk_intervenant_id")
     */
    private $disponibilites;

    public function __construct()
    {
        $this->matieres = new ArrayCollection();
        $this->cours = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->disponibilites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getSpecialiteprofessionnelle(): ?string
    {
        return $this->specialiteprofessionnelle;
    }

    public function setSpecialiteprofessionnelle(?string $specialiteprofessionnelle): self
    {
        $this->specialiteprofessionnelle = $specialiteprofessionnelle;

        return $this;
    }

    /**
     * @return Collection|Matiere[]
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres[] = $matiere;
            $matiere->setFkIntervenant($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        if ($this->matieres->contains($matiere)) {
            $this->matieres->removeElement($matiere);
            // set the owning side to null (unless already changed)
            if ($matiere->getFkIntervenant() === $this) {
                $matiere->setFkIntervenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cours[]
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours[] = $cour;
            $cour->setFkIntervenantId($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->contains($cour)) {
            $this->cours->removeElement($cour);
            // set the owning side to null (unless already changed)
            if ($cour->getFkIntervenantId() === $this) {
                $cour->setFkIntervenantId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setFkIntervenantId($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getFkIntervenantId() === $this) {
                $notification->setFkIntervenantId(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Disponibilite[]
     */
    public function getDisponibilites(): Collection
    {
        return $this->disponibilites;
    }

    public function addDisponibilite(Disponibilite $disponibilite): self
    {
        if (!$this->e->contains($disponibilite)) {
            $this->disponibilites[] = $disponibilite;
            $disponibilite->setFkIntervenantId($this);
        }

        return $this;
    }

    public function removeDisponibilite(Disponibilite $disponibilite): self
    {
        if ($this->disponibilites->contains($disponibilite)) {
            $this->disponibilites->removeElement($disponibilite);
            // set the owning side to null (unless already changed)
            if ($disponibilite->getFkIntervenantId() === $this) {
                $disponibilite->setFkIntervenantId(null);
            }
        }

        return $this;
    }
}
