<?php

namespace App\Repository;

use App\Entity\Intolerance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Intolerance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intolerance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intolerance[]    findAll()
 * @method Intolerance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntoleranceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Intolerance::class);
    }

    // /**
    //  * @return Intolerance[] Returns an array of Intolerance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Intolerance
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
