<?php

namespace App\Repository;

use App\Entity\GuestPlusOne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GuestPlusOne|null find($id, $lockMode = null, $lockVersion = null)
 * @method GuestPlusOne|null findOneBy(array $criteria, array $orderBy = null)
 * @method GuestPlusOne[]    findAll()
 * @method GuestPlusOne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuestPlusOneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuestPlusOne::class);
    }

    // /**
    //  * @return GuestPlusOne[] Returns an array of GuestPlusOne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GuestPlusOne
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
