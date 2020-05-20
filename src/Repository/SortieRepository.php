<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Entity\SortieFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function findSorties(SortieFilter $filter)
    {
        $qb = $this->createQueryBuilder('s');
        if($filter->getName() || $filter->getName() != null){
            $qb->andWhere('s.nom LIKE :nom')
                ->setParameter('nom', $filter->getName());
        }

        if($filter->getName() || $filter->getName() != null){
            $qb->andWhere('s.nom LIKE :nom')
                ->setParameter('nom', $filter->getName());
        }

        $query = $qb->getQuery();
        return new Paginator($query);
    }
}
