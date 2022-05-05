<?php

namespace App\Repository;

use App\Entity\Guest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Guest|null find($id, $lockMode = null, $lockVersion = null)
 * @method Guest|null findOneBy(array $criteria, array $orderBy = null)
 * @method Guest[]    findAll()
 * @method Guest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guest::class);
    }

    public function getGuestsFromResearch($query)
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
