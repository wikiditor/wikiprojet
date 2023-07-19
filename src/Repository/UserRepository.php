<?php

namespace App\Repository;

use App\Document\User;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceDocumentRepository<User>
 * @implements PasswordUpgraderInterface<User>
 */
class UserRepository extends ServiceDocumentRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush();
    }

      // ajoute un user (document) à la collection
      public function addUser(User $user)
      {
          $this->getDocumentManager()->persist($user);
          $this->getDocumentManager()->flush();
      }

      //récupère tous les users (documents) de la collection
      public function findAllUsers()
    {
        $this->findAll();
    }

    public function saveUser($user)
    {
        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush();
    }
}
