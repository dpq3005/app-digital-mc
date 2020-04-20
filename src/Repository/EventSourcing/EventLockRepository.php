<?php

namespace App\Repository\EventSourcing;

use App\Entity\EventSourcing\EventLock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EventLock|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventLock|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventLock[]    findAll()
 * @method EventLock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventLockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventLock::class);
    }

    // /**
    //  * @return EventLock[] Returns an array of EventLock objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventLock
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
