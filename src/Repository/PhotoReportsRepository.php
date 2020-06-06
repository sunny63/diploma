<?php

namespace App\Repository;

use App\Entity\PhotoReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PhotoReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhotoReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhotoReport[]    findAll()
 * @method PhotoReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoReportsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhotoReport::class);
    }

    // /**
    //  * @return PhotoReport[] Returns an array of PhotoReport objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PhotoReport
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
