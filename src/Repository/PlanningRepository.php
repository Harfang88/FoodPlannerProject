<?php

namespace App\Repository;

use App\Entity\Planning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Planning|null find($id, $lockMode = null, $lockVersion = null)
 * @method Planning|null findOneBy(array $criteria, array $orderBy = null)
 * @method Planning[]    findAll()
 * @method Planning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanningRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Planning::class);
    }

    public function findPlanningBetweenDate($fromDate, $toDate, $userId)
    {

        $to = new \DateTime($toDate->format("Y-m-d")." 23:59:59");

        $query = $this->createQueryBuilder('p')
                      ->leftJoin('p.user', 'u')
                      ->where('p.mealDay BETWEEN :from AND :to')
                      ->andWhere('u.id = :id')
                      ->setParameter('from', $fromDate)
                      ->setParameter('to', $to)
                      ->setParameter('id', $userId)
                      ->getQuery();

        return $query->execute();
    }

    public function findPlanningByUser($fromDate, $toDate,$userId)
    {
        $to = new \DateTime($toDate->format("Y-m-d")." 23:59:59");

        $query = $this->createQueryBuilder('p')
                      ->leftJoin('p.recipe','r')
                      ->leftJoin('p.user', 'u')
                      ->select('p.mealTime')
                      ->addSelect('p.mealDay')
                      ->addSelect('r.title')
                      ->addSelect('p.id')
                      ->where('u.id = :id')
                      ->andWhere('p.mealDay BETWEEN :from AND :to')
                      ->setParameter('from', $fromDate)
                      ->setParameter('to', $to)
                      ->setParameter('id', $userId)
                      ->orderBy('p.mealDay', 'ASC')
                      ->getQuery();

        return $query->execute();
    }
}
