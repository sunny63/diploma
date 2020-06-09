<?php

namespace App\Repository;

use App\Entity\Stock;
use App\Entity\Post;
use App\Entity\PhotoReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stock[]    findAll()
 * @method Stock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }

    /**
     * @return Stock[]
     */
    public function findAllPastStock($date_end): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s
            FROM App\Entity\Stock s
            WHERE s.date_end < :date_end
            ORDER BY s.date_end ASC'
        )->setParameter('date_end', $date_end);

        // returns an array of Product objects
        return $query->getResult();
    }

    /**
     * @return Stock[]
     */
    public function findAllStocksOrderByDate(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s
            FROM App\Entity\Stock s
            ORDER BY s.date_end DESC'
        );

        // returns an array of Product objects
        return $query->getResult();
    }


//    /**
//     * @return Stock[]
//     */
//    public function findAllOrderByDate(): array
//    {
//        $entityManager = $this->getEntityManager();
//
//        $query = $entityManager->createQuery(
//            'SELECT stocks, posts, photoReports
//            FROM App\Entity\Stock stocks INNER JOIN App\Entity\Post posts INNER JOIN App\Entity\PhotoReport photoReports
//            ORDER BY photoReports.create_at, posts.create_at, stocks.create_at ASC'
//        );
//
//        // returns an array of Product objects
//        return $query->getResult();
//    }


    // /**
    //  * @return Stock[] Returns an array of Stock objects
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
    public function findOneBySomeField($value): ?Stock
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
