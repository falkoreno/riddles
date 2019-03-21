<?php

namespace App\Repository;

use App\Entity\Riddles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Riddles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Riddles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Riddles[]    findAll()
 * @method Riddles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RiddlesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Riddles::class);
    }

    // /**
    //  * @return Riddles[] Returns an array of Riddles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Riddles
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
