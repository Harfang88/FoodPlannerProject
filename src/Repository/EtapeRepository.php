<?php

namespace App\Repository;

use App\Entity\Etape;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Etape|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etape|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etape[]    findAll()
 * @method Etape[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtapeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Etape::class);
    }

    public function findAllCustom()
    {
        $query = $this->createQueryBuilder('e')
                      ->orderBy('e.etapeOrder')
                      ->getQuery();
        
        return $query->execute();
    }
}
