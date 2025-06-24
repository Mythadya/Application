<?php

namespace App\Entity;
use DateTimeInterface;

use App\Repository\JourFerieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JourFerieRepository::class)]
class JourFerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

#[ORM\Column(type: 'date')]
private ?\DateTimeInterface $date = null;


    #[ORM\Column(length: 255)]
    private ?string $nom = null;

// src/Entity/JourFerie.php
#[ORM\Column(length: 50)]
private ?string $zone = 'metropole';

#[ORM\Column(type: 'integer', nullable: true)]
private ?int $annee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
      
        return $this;
    }

   public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }
  

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): static
    {
        $this->zone = $zone;
        return $this;
    }

     public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): static
    {
        $this->annee = $annee;
        return $this;
    }

}





























