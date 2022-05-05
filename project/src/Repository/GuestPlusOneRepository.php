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

    public function getGuestsPlusOneFromResearch($query)
    {
        $qb = $this->createQueryBuilder('entity')
            ->where("(LOWER(entity.lastName) LIKE LOWER(:query))")
            ->orWhere("(LOWER(entity.firstName) LIKE LOWER(:query))")
            ->setParameter('query', "%".$query."%")
            ->orderBy('entity.lastName', 'ASC')
            ->addOrderBy('entity.firstName', 'ASC')
        ;

        return $qb->getQuery()->execute();
    }
}
