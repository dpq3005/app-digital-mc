<?php

namespace App\Repository\Dmc;

use App\Entity\Dmc\MedicalChit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MedicalChit|null find($id, $lockMode = null, $lockVersion = null)
 * @method MedicalChit|null findOneBy(array $criteria, array $orderBy = null)
 * @method MedicalChit[]    findAll()
 * @method MedicalChit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicalChitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedicalChit::class);
    }

    // /**
    //  * @return MedicalChit[] Returns an array of MedicalChit objects
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
    public function findOneBySomeField($value): ?MedicalChit
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
