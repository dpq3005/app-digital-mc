<?php

namespace App\Repository\EventSourcing;

use App\EntityLogging\EventSourcing\SyncProgress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SyncProgress|null find($id, $lockMode = null, $lockVersion = null)
 * @method SyncProgress|null findOneBy(array $criteria, array $orderBy = null)
 * @method SyncProgress[]    findAll()
 * @method SyncProgress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SyncProgressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SyncProgress::class);
    }

    // /**
    //  * @return SyncProgress[] Returns an array of SyncProgress objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SyncProgress
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
