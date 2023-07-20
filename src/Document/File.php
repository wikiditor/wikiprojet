<?php

namespace App\Document;

use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use DateTimeZone;

#[MongoDB\Document]
class File
{
    #[MongoDB\Id]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    private string $title;

    #[MongoDB\Field(type: 'string')]
    private string $content;

    #[MongoDB\Field(type: 'date')]
    private ?\DateTime $creationDate = null;

    #[MongoDB\Field(type: 'date')]
    private ?\DateTime $lastUpdate = null;

    // #[MongoDB\UserId]
    private string $userId;

    /**
     * @MongoDB\ReferenceOne(targetDocument=User::class)
     */
    private ?User $user = null;



    public function getId(): string
    {
        return $this->id;
    }
    public function setId(string $id): File
    {
        $this->id = $id;

        return $this;
    }


    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $title): File
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }
    public function setContent(string $content): File
    {
        $this->content = $content;
        $this->lastUpdate = new DateTime('now', new DateTimeZone('Europe/Paris'));
        return $this;
    }


    public function getCreationDate(): ?\DateTime
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTime $creationDate): File
    {
        $this->creationDate = $creationDate;
        return $this;
    }


    public function getLastUpdate(): ?\DateTime
    {
        return $this->lastUpdate;
    }
    public function setLastUpdate(\DateTime $lastUpdate): File
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function getuserId(): string
    {
        return $this->userId;
    }
    public function setUserId(string $userId): File
    {
        $this->userId = $userId;

        return $this;
    }
    public function __construct()
    {
        $this->creationDate = new \DateTime('now', new DateTimeZone('Europe/Paris'));
    }
    
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): File
    {
        $this->user = $user;

        return $this;
    }
}
