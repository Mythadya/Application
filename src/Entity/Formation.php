<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $actifFormation = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

#[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
private ?\DateTime $dateDebutValidation = null;

#[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
private ?\DateTime $dateFinValidation = null;




//  #[ORM\Column(type: Types::DATE_MUTABLE)]
// private ?\DateTime $dateDebutValidation;

// #[ORM\Column(type: Types::DATE_MUTABLE)]
// private ?\DateTime $dateFinValidation;


    #[ORM\Column(length: 255)]
    private ?string $titreProfessionnel = null;

  // #[ORM\Column(length: 255)]
  //  private ?string $titreProfessionnel = null;

    #[ORM\Column]
    private ?int $niveau = null;


    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $NombreStagiaires = null;


    //#[ORM\Column(type: 'integer', nullable: true)]
    private ?int $activeStagiaires = null;
    // #[ORM\Column(length: 255)]
    // private ?int $nbStagiairesPrevisionnel = null;

    #[ORM\Column(length: 255)]
    private ?string $groupeRattachement = null;

    #[ORM\Column(type: 'integer', nullable:true)]
   private ?int $nombreHeures = null; // added at 12062025

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateDebut = null;


    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
        private ?\DateTime $dateFin = null;
    // #[ORM\Column(type: Types::DATE_MUTABLE)]
    // private ?\DateTime $dateFin = null;
    

    #[ORM\ManyToMany(targetEntity: Formateur::class, inversedBy: 'formations')]
    private Collection $formateurs;

    #[ORM\OneToMany(targetEntity: Interruption::class, mappedBy: 'formation')]
    private Collection $interruptions;

    #[ORM\OneToMany(targetEntity: PeriodEnEntreprise::class, mappedBy: 'formation')]
    private Collection $periodEnEntreprises;

    public function __construct()
    {
        $this->interruptions = new ArrayCollection();
        $this->periodEnEntreprises = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isActifFormation(): ?bool
    {
        return $this->actifFormation;
    }

    public function setActifFormation(bool $actifFormation): static
    {
        $this->actifFormation = $actifFormation;
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

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;
        return $this;
    }

   public function getDateDebutValidation(): ?\DateTime
{
    return $this->dateDebutValidation;
}

public function setDateDebutValidation(?\DateTime $dateDebutValidation): static
{
    $this->dateDebutValidation = $dateDebutValidation;
    return $this;
}

public function getDateFinValidation(): ?\DateTime
{
    return $this->dateFinValidation;
}

public function setDateFinValidation(?\DateTime $dateFinValidation): static
{
    $this->dateFinValidation = $dateFinValidation;
    return $this;
}




    // public function setDateDebutValidation(?\DateTime $dateDebutValidation): static
    // {
    //     $this->dateDebutValidation = $dateDebutValidation;
    //     return $this;
    // }

    //   public function getDateFinValidation(): ?\DateTime
    // {
    //     return $this->dateFinValidation;
    // }

    // public function setDateFinValidation(?\DateTime $dateFinValidation): static
    // {
    //     $this->dateFinValidation = $dateFinValidation;
    //     return $this;
    // }





public function getNombreHeures(): ?int
{
    return $this->nombreHeures;
}

public function setNombreHeures(int $nombreHeures): static
{
    $this->nombreHeures = $nombreHeures;
    return $this;
}





    public function getTitreProfessionnel(): ?string
    {
        return $this->titreProfessionnel;
    }

    public function setTitreProfessionnel(string $titreProfessionnel): static
    {
        $this->titreProfessionnel = $titreProfessionnel;
        return $this;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(int $niveau): static
    {
        $this->niveau = $niveau;
        return $this;
    }

    public function getNombreStagiaires(): ?int
    {
        return $this->NombreStagiaires;
    }

    public function setNombreStagiaires(int $NombreStagiaires): static
    {
        $this->NombreStagiaires = $NombreStagiaires;
        return $this;
    }


public function getActiveStagiaires(): ?int
{
    return $this->activeStagiaires;
}

public function setActiveStagiaires(?int $count): static
{
    $this->activeStagiaires = $count;
    return $this;
}

    public function getGroupeRattachement(): ?string
    {
        return $this->groupeRattachement;
    }

    public function setGroupeRattachement(string $groupeRattachement): static
    {
        $this->groupeRattachement = $groupeRattachement;
        return $this;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTime $dateDebut): static
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }


public function getDateFin(): ?\DateTime
{
    return $this->dateFin;
}

public function setDateFin(?\DateTime $dateFin): static
{
    $this->dateFin = $dateFin;
    return $this;
}





    // public function getDateFin(): ?\DateTime
    // {
    //     return $this->dateFin;
    // }

    // public function setDateFin(\DateTime $dateFin): static
    // {
    //     $this->dateFin = $dateFin;
    //     return $this;
    // }

    /**
     * @return Collection<int, Formateur>
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): static
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): static
    {
        $this->formateurs->removeElement($formateur);
        return $this;
    }

    /**
     * @return Collection<int, Interruption>
     */
    public function getInterruptions(): Collection
    {
        return $this->interruptions;
    }

    public function addInterruption(Interruption $interruption): static
    {
        if (!$this->interruptions->contains($interruption)) {
            $this->interruptions->add($interruption);
            $interruption->setFormation($this);
        }

        return $this;
    }

    public function removeInterruption(Interruption $interruption): static
    {
        if ($this->interruptions->removeElement($interruption)) {
            if ($interruption->getFormation() === $this) {
                $interruption->setFormation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PeriodEnEntreprise>
     */
    public function getPeriodEnEntreprises(): Collection
    {
        return $this->periodEnEntreprises;
    }

    public function addPeriodEnEntreprise(PeriodEnEntreprise $periodEnEntreprise): static
    {
        if (!$this->periodEnEntreprises->contains($periodEnEntreprise)) {
            $this->periodEnEntreprises->add($periodEnEntreprise);
            $periodEnEntreprise->setFormation($this);
        }

        return $this;
    }

    public function removePeriodEnEntreprise(PeriodEnEntreprise $periodEnEntreprise): static
    {
        if ($this->periodEnEntreprises->removeElement($periodEnEntreprise)) {
            if ($periodEnEntreprise->getFormation() === $this) {
                $periodEnEntreprise->setFormation(null);
            }
        }

        return $this;
    }
}

