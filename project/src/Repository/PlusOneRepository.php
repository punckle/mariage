<?php

namespace App\Repository;

use App\Entity\PlusOne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlusOne|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlusOne|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlusOne[]    findAll()
 * @method PlusOne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlusOneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlusOne::class);
    }
}
