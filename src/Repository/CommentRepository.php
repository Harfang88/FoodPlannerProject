<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
    * @return Comment[] 
    */
    
    public function commentsByDate()
    {
        return $this->createQueryBuilder('c')
            ->join('c.author', 'a')
            ->join('c.recipe', 'r')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
        ;
    }

    public function findUnblockedComment($recipe)
    {
        return $this->createQueryBuilder('c')
            ->join('c.author', 'a')
            ->select('c.id')
            ->addSelect('a.username')
            ->addSelect('c.body')
            ->addSelect('c.createdAt')
            ->where('c.isBlocked = :status')
            ->andWhere('c.recipe = :recipe')
            ->setParameter('status', false)
            ->setparameter('recipe', $recipe)
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
