<?php

namespace App\Repository\Dmc;

use App\Entity\Dmc\MerchantAssignment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MerchantAssignment|null find($id, $lockMode = null, $lockVersion = null)
 * @method MerchantAssignment|null findOneBy(array $criteria, array $orderBy = null)
 * @method MerchantAssignment[]    findAll()
 * @method MerchantAssignment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MerchantAssignmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MerchantAssignment::class);
    }

    // /**
    //  * @return MerchantAssignment[] Returns an array of MerchantAssignment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MerchantAssignment
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
