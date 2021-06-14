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

    /**
     * @return PhotoReport[]
     */
    public function findAllPhotoReportsOrderByDate(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\PhotoReport p
            ORDER BY p.create_at DESC'
        );

        // returns an array of Product objects
        return $query->getResult();
    }

    /**
     * @return PhotoReport[]
     */
    public function findNewPhotoReports($dateWithout20Days): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\PhotoReport p
            WHERE p.create_at > :date_end
            ORDER BY p.create_at DESC'
        )->setParameter('date_end', $dateWithout20Days);

        // returns an array of Product objects
        return $query->getResult();
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
