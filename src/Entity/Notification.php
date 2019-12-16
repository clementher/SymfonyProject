<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 */
class Notification
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
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Intervenant", inversedBy="notifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_intervenant_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_read;

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

    public function getFkIntervenantId(): ?Intervenant
    {
        return $this->fk_intervenant_id;
    }

    public function setFkIntervenantId(?Intervenant $fk_intervenant_id): self
    {
        $this->fk_intervenant_id = $fk_intervenant_id;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->is_read;
    }

    public function setIsRead(bool $is_read): self
    {
        $this->is_read = $is_read;

        return $this;
    }
}
