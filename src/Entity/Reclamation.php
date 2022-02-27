<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le champ est requis")
    */
    private $sujet_rec;
      /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le champ est requis")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $niveau;


  

    
    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujetRec(): ?string
    {
        return $this->sujet_rec;
    }
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setSujetRec(string $sujet_rec): self
    {
        $this->sujet_rec = $sujet_rec;

        return $this;
    }
    public function settype(string $type): self
    {
        $this->type = $type;

        return $this;
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

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

   

}
