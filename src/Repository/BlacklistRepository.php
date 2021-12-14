<?php

namespace App\Repository;

use App\Entity\Blacklist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Blacklist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blacklist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blacklist[]    findAll()
 * @method Blacklist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlacklistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blacklist::class);
    }

    // /**
    //  * @return Blacklist[] Returns an array of Blacklist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Blacklist
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
