<?php

namespace App\Repository;

use App\Document\User;
use App\Document\File;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class FileRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    public function findAllFiles()
    {
        $this->findAll();
    }

    public function saveFile($file)
    {
        $this->getDocumentManager()->persist($file);
        $this->getDocumentManager()->flush();
    }
    public function remove(File $file): void
    {
        $this->getDocumentManager()->remove($file);
        $this->getDocumentManager()->flush();
    }
    public function findByUser(User $user): array
    {
        return $this->findBy(['user' => $user]);
    }
    public function findByUserSortedByLastUpdate(User $user): array
    {
        return $this->createQueryBuilder()
            ->field('user')->equals($user)
            ->sort('lastUpdate', 'desc')
            ->getQuery()
            ->execute()
            ->toArray();
    }
}
