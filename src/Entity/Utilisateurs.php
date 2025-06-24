<?php

namespace App\Entity;

use App\Repository\UtilisateursRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
class Utilisateurs implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;


   #[ORM\Column(length: 5)]
    private ?string $codepostal = null;

    #[ORM\Column(length: 150)]
    private ?string $ville = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private string $role = 'ROLE_CONSULTATION';

    #[ORM\Column]
    private ?\DateTimeImmutable $dateInvitation = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }


    public function getCodepostal(): ?string
    {
        return $this->codepostal;
    }

    public function setCodepostal(string $codepostal): static
    {
        $this->codepostal = $codepostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getDateInvitation(): ?\DateTimeImmutable
    {
        return $this->dateInvitation;
    }

    public function setDateInvitation(\DateTimeImmutable $dateInvitation): static
    {
        $this->dateInvitation = $dateInvitation;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password ?? '';
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        // Returns array to satisfy UserInterface but maintains single-role system
        return [$this->role];
    }

    public function eraseCredentials(): void
    {
        // Clear any temporary sensitive data
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getSalt(): ?string
    {
        // Not needed with modern hashing
        return null;
    }

    // Role check methods (direct comparisons matching cahier des charges)
    public function isAdmin(): bool
    {
        return $this->role === 'ROLE_ADMIN';
    }

    public function isGestionnaire(): bool
    {
        return $this->role === 'ROLE_GESTIONNAIRE';
    }

    public function isConsultation(): bool
    {
        return $this->role === 'ROLE_CONSULTATION';
    }

}



















// namespace App\Entity;

// use App\Repository\UtilisateursRepository;
// use Doctrine\ORM\Mapping as ORM;
// use Symfony\Component\Security\Core\User\UserInterface;
// use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

// #[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
// class Utilisateurs implements UserInterface, PasswordAuthenticatedUserInterface
// {
//     #[ORM\Id]
//     #[ORM\GeneratedValue]
//     #[ORM\Column]
//     private ?int $id = null;

//     #[ORM\Column(length: 255)]
//     private ?string $nom = null;

//     #[ORM\Column(length: 255)]
//     private ?string $prenom = null;

//     #[ORM\Column(length: 255, unique: true)]
//     private ?string $email = null;

//     #[ORM\Column(length: 255)]
//     private string $role = 'ROLE_CONSULTATION';

//     #[ORM\Column]
//     private ?\DateTimeImmutable $dateInvitation = null;

//     #[ORM\Column(length: 255)]
//     private ?string $password = null;

//     public function getId(): ?int
//     {
//         return $this->id;
//     }

//     public function getNom(): ?string
//     {
//         return $this->nom;
//     }

//     public function setNom(string $nom): static
//     {
//         $this->nom = $nom;
//         return $this;
//     }

//     public function getPrenom(): ?string
//     {
//         return $this->prenom;
//     }

//     public function setPrenom(string $prenom): static
//     {
//         $this->prenom = $prenom;
//         return $this;
//     }

//     public function getEmail(): ?string
//     {
//         return $this->email;
//     }

//     public function setEmail(string $email): static
//     {
//         $this->email = $email;
//         return $this;
//     }

//     public function getRole(): string
//     {
//         return $this->role;
//     }

//     public function setRole(string $role): static
//     {
//         $this->role = $role;
//         return $this;
//     }

//     public function getDateInvitation(): ?\DateTimeImmutable
//     {
//         return $this->dateInvitation;
//     }

//     public function setDateInvitation(\DateTimeImmutable $dateInvitation): static
//     {
//         $this->dateInvitation = $dateInvitation;
//         return $this;
//     }

//     public function getPassword(): string
//     {
//         return $this->password ?? '';
//     }

//     public function setPassword(string $password): static
//     {
//         $this->password = $password;
//         return $this;
//     }

//     public function getRoles(): array
//     {
//         return [$this->role];
//     }

//     public function eraseCredentials(): void
//     {
//         // Clear any temporary sensitive data
//     }

//     public function getUserIdentifier(): string
//     {
//         return $this->email;
//     }

//     public function getSalt(): ?string
//     {
//         return null;
//     }

//     public function isAdmin(): bool
//     {
//         return $this->role === 'ROLE_ADMIN';
//     }

//     public function isGestionnaire(): bool
//     {
//         return $this->role === 'ROLE_GESTIONNAIRE';
//     }

//     public function isConsultation(): bool
//     {
//         return $this->role === 'ROLE_CONSULTATION';
//     }

//     public function hasRole(string $role): bool
//     {
//         $hierarchy = [
//             'ROLE_ADMIN' => ['ROLE_ADMIN', 'ROLE_GESTIONNAIRE', 'ROLE_CONSULTATION'],
//             'ROLE_GESTIONNAIRE' => ['ROLE_GESTIONNAIRE', 'ROLE_CONSULTATION'],
//             'ROLE_CONSULTATION' => ['ROLE_CONSULTATION']
//         ];

//         return in_array($role, $hierarchy[$this->role] ?? [], true);
//     }
// }





















// namespace App\Entity;

// use App\Repository\UtilisateursRepository;
// use Doctrine\ORM\Mapping as ORM;

// #[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
// class Utilisateurs
// {
//     #[ORM\Id]
//     #[ORM\GeneratedValue]
//     #[ORM\Column]
//     private ?int $id = null;

//     #[ORM\Column(length: 255)]
//     private ?string $nom = null;

//     #[ORM\Column(length: 255)]
//     private ?string $prenom = null;

//     #[ORM\Column(length: 255)]
//     private ?string $email = null;

//     #[ORM\Column(length: 255)]
//     private ?string $role = null;

//     #[ORM\Column]
//     private ?\DateTime $dateInvitation = null;

//     public function getId(): ?int
//     {
//         return $this->id;
//     }

//     public function getNom(): ?string
//     {
//         return $this->nom;
//     }

//     public function setNom(string $nom): static
//     {
//         $this->nom = $nom;

//         return $this;
//     }

//     public function getPrenom(): ?string
//     {
//         return $this->prenom;
//     }

//     public function setPrenom(string $prenom): static
//     {
//         $this->prenom = $prenom;

//         return $this;
//     }

//     public function getEmail(): ?string
//     {
//         return $this->email;
//     }

//     public function setEmail(string $email): static
//     {
//         $this->email = $email;

//         return $this;
//     }

//     public function getRole(): ?string
//     {
//         return $this->role;
//     }

//     public function setRole(string $role): static
//     {
//         $this->role = $role;

//         return $this;
//     }

//     public function getDateInvitation(): ?\DateTime
//     {
//         return $this->dateInvitation;
//     }

//     public function setDateInvitation(\DateTime $dateInvitation): static
//     {
//         $this->dateInvitation = $dateInvitation;

//         return $this;
//     }
// }
