<?php

namespace App\Repository\BenefitProvider;

use App\Entity\BenefitProvider\BenefitProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BenefitProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method BenefitProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method BenefitProvider[]    findAll()
 * @method BenefitProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BenefitProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BenefitProvider::class);
    }

    // /**
    //  * @return BenefitProvider[] Returns an array of BenefitProvider objects
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
    public function findOneBySomeField($value): ?BenefitProvider
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
