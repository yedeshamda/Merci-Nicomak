<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherInterface;

/**
 * @ORM\Entity(repositoryClass=EmployeeRepository::class)
 */
class Employee implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user (e.g. username).
     *
     * @see UserInterface
     */
//     public function getUsername(): string
//     {
//         return (string) $this->name;
//     }
    public function getUsername(): string
    {
        return (string) $this->username; // Assurez-vous d'utiliser la bonne propriété ici.
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->username; // ou autre identifiant unique
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

   /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // Retourner un tableau de rôles, ici vous pouvez définir un rôle par défaut pour l'employé
        return ['ROLE_USER'];
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        // bcrypt and sodium don't require a separate salt.
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
