<?php
// src/Entity/User.php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'UTILISATEURS')] 
#[UniqueEntity(fields: ['email'], message: 'Un compte avec cet email existe déjà.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'IdUtilisateur', type: 'integer')] 
    private ?int $id = null;

    #[ORM\Column(name: 'Email', length: 255, unique: true)] 
    private ?string $email = null;

    #[ORM\Column(name: 'Password', length: 255)] 
    private ?string $password = null;

    #[ORM\Column(name: 'Nom', length: 100)] 
    private ?string $nom = null;

    #[ORM\Column(name: 'Prenom', length: 100, nullable: true)] 
    private ?string $prenom = null;

    #[ORM\Column(name: 'DateInscription', type: Types::DATETIME_MUTABLE, nullable: true)] 
    private ?\DateTimeInterface $dateInscription = null;

    #[ORM\Column(name: 'Role', type: 'string', length: 10)] 
    private string $role = 'USER';

    private ?string $plainPassword = null;

    public function __construct()
    {
        $this->dateInscription = new \DateTime();
        $this->role = 'USER';
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        switch ($this->role) {
            case 'ADMIN':
                return ['ROLE_ADMIN', 'ROLE_USER'];
            default:
                return ['ROLE_USER'];
        }
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

    public function setRoles(array $roles): static
    {
        if (in_array('ROLE_ADMIN', $roles)) {
            $this->role = 'ADMIN';
        } else {
            $this->role = 'USER';
        }
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
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

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(?\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;
        return $this;
    }
}