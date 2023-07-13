<?php

namespace App\Document;

use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

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
    private \DateTime $creationDate;

    #[MongoDB\Field(type: 'date')]
    private \DateTime $saveDate;

    // #[MongoDB\UserId]
    private string $userId;

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
    public function settitle(string $title): File
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

        return $this;
    }

    public function getCreationDate(): \DateTime
    {
        return $this->creationDate;
    }
    public function setCreationDate(\DateTime $creationDate): File
    {
        $this->creationDate = $creationDate;

        return $this;
    }


    public function getSaveDate(): \DateTime
    {
        return $this->saveDate;
    }
    public function setSaveDate(\DateTime $saveDate): File
    {
        $this->saveDate = $saveDate;

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
    
}