<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoursRepository")
 */
class Cours
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $debut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\matiere", inversedBy="cours")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_matiere_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Intervenant", inversedBy="cours")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_intervenant_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getFkMatiereId(): ?matiere
    {
        return $this->fk_matiere_id;
    }

    public function setFkMatiereId(?matiere $fk_matiere_id): self
    {
        $this->fk_matiere_id = $fk_matiere_id;

        return $this;
    }

    public function getFkIntervenantId(): ?Intervenant
    {
        return $this->fk_intervenant_id;
    }

    public function setFkIntervenantId(?Intervenant $fk_intervenant_id): self
    {
        $this->fk_intervenant_id = $fk_intervenant_id;

        return $this;
    }
}
