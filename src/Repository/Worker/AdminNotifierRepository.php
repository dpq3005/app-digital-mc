<?php

namespace App\Repository\Worker;

use App\Entity\Worker\AdminNotifier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdminNotifier|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminNotifier|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminNotifier[]    findAll()
 * @method AdminNotifier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminNotifierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminNotifier::class);
    }

    // /**
    //  * @return AdminNotifier[] Returns an array of AdminNotifier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdminNotifier
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
