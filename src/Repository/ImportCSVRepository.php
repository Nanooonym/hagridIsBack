<?php

namespace App\Repository;

use App\Entity\ImportCSV;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImportCSV|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImportCSV|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImportCSV[]    findAll()
 * @method ImportCSV[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImportCSVRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImportCSV::class);
    }

    // /**
    //  * @return ImportCSV[] Returns an array of ImportCSV objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImportCSV
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
