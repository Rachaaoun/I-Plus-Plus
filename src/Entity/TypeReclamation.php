<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TypeReclamation
 *
 * @ORM\Table(name="type_reclamation")
 * @ORM\Entity
 */
class TypeReclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_type_recla", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTypeRecla;

    /**
     * @var string
     *
     * @ORM\Column(name="niveau", type="string", length=60, nullable=false)
     */
    private $niveau;

    /**
     * @ORM\ManyToMany(targetEntity=Reclamation::class, mappedBy="type_rec")
     */
    private $reclamations;

    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
    }

    public function getIdTypeRecla(): ?int
    {
        return $this->idTypeRecla;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * @return Collection|Reclamation[]
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations[] = $reclamation;
            $reclamation->addTypeRec($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            $reclamation->removeTypeRec($this);
        }

        return $this;
    }


}
