<?php

namespace App\Document;

// Importe les classes nécessaires pour la création du document User.
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// Définition du document User qui implémente UserInterface et PasswordAuthenticatedUserInterface.
// UserInterface est utilisé par Symfony pour le système de sécurité.
#[MongoDB\Document(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $email = null;

    #[MongoDB\Field(type: 'string')]
    private string $alias;

    #[MongoDB\Field(type: 'collection')]
    private array $roles = ['ROLE_USER'];

    #[MongoDB\Field(type: 'string')]
    private ?string $password = null;

    #[MongoDB\Field(type: 'string')]
    private string $lastName;

    #[MongoDB\Field(type: 'string')]
    private string $firstName;

    #[MongoDB\Field(type: 'string')]
    private string $picture;

    #[MongoDB\Field(type: 'bool')]
    private $isVerified = false;

    // Ajoute le champ blocked
    #[MongoDB\Field(type: 'bool')]
    private $blocked = false;

    // Constructeur de la classe
    public function __construct()
    {
        $this->alias = '';
    }

    // getters et setters

    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(string $id): static
    {
        $this->id = $id;

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

    public function getAlias(): string
{
    if (!isset($this->alias)) {
        $this->alias = '';
    }
    return $this->alias;
}
    public function setAlias(string $alias): static
{
    if (!isset($this->alias)) {
        $this->alias = '';
    }
    $this->alias = $alias;

    return $this;
}


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }
    public function setPicture(string $picture): User
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    // Ajoute les méthodes pour le champ blocked
    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    public function setBlocked(bool $blocked): static
    {
        $this->blocked = $blocked;

        return $this;
    }
}
