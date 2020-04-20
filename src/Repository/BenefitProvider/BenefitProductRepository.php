<?php

namespace App\Repository\BenefitProvider;

use App\Entity\BenefitProvider\BenefitProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BenefitProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method BenefitProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method BenefitProduct[]    findAll()
 * @method BenefitProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BenefitProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BenefitProduct::class);
    }

    // /**
    //  * @return BenefitProduct[] Returns an array of BenefitProduct objects
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
    public function findOneBySomeField($value): ?BenefitProduct
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
