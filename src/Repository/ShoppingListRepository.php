<?php

namespace App\Repository;

use App\Entity\ShoppingList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ShoppingList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoppingList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoppingList[]    findAll()
 * @method ShoppingList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppingListRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShoppingList::class);
    }

}
