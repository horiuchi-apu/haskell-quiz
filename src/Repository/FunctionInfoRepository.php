<?php

namespace App\Repository;

use App\Entity\FunctionInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FunctionInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method FunctionInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method FunctionInfo[]    findAll()
 * @method FunctionInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FunctionInfoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FunctionInfo::class);
    }

//    /**
//     * @return FunctionInfo[] Returns an array of FunctionInfo objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FunctionInfo
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
