<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class User
{
    #[MongoDB\Id]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    private string $alias;

    #[MongoDB\Field(type: 'string')]
    private string $email;

    #[MongoDB\Field(type: 'string')]
    private string $password;

    #[MongoDB\Field(type: 'string')]
    private string $lastName;

    #[MongoDB\Field(type: 'string')]
    private string $firstName;

    #[MongoDB\Field(type: 'string')]
    private string $picture;


    public function getId(): string
    {
        return $this->id;
    }
    public function setId(string $id): User
    {
        $this->id = $id;

        return $this;
    }


    public function getAlias(): string
    {
        return $this->alias;
    }
    public function setAlias(string $alias): User
    {
        $this->alias = $alias;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    public function setPassword(string $password): User
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
    
   

    
}