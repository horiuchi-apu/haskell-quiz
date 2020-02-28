<?php

namespace App\Repository;

use App\Entity\AdminUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * @method AdminUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminUser[]    findAll()
 * @method AdminUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminUserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminUser::class);
    }

    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('admin_user')
            ->where('admin_user.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getResult();
    }
}
