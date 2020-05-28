<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Entity\SortieFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\Query\Expr;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @var Security
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sortie::class);
        $this->security = $security;
    }

    private $security;

    public function findSorties(SortieFilter $filter)
    {
        $qb = $this->createQueryBuilder('s');
        $user = $this->security->getUser();

        $qb->leftJoin('s.participants', 'p');
        $qb->addSelect('p');
        $qb->leftjoin('s.organisateur', 'o');
        $qb->addSelect('o');


        $qb->andWhere("DATE_ADD(DATE_ADD(s.dateDebut, s.duree, 'minute'), 1, 'month') > :now")
            ->setParameter('now', new \DateTime("now"));

        if($filter->getCampus() || $filter->getCampus() != null){
            $qb->andWhere('s.campus = :campus')
                ->setParameter('campus', $filter->getCampus());
        }

        if($filter->getName() || $filter->getName() != null){
            $qb->andWhere('s.nom LIKE :nom')
                ->setParameter('nom', "%" . $filter->getName() . "%");
        }

        if($filter->getDateDebut() || $filter->getDateDebut() != null){
            $qb->andWhere('s.dateDebut > :dateDebut')
                ->setParameter('dateDebut', $filter->getDateDebut());
        }

        if($filter->getDateFin() || $filter->getDateFin() != null){
            $qb->andWhere('s.dateDebut < :dateCloture')
                ->setParameter('dateCloture', $filter->getDateFin());
        }

        $orQuery = new Expr\Orx();

        if($filter->getIsOrganisateur()) {
            $orQuery->add('o.pseudo LIKE :user');
        }

        if($filter->getIsInscrit()) {
            $orQuery->add('p.pseudo LIKE :user');
        }

        if($filter->getIsNotInscrit()){
            $orQuery->add('p.pseudo NOT LIKE :user');
        }


        if($filter->getPassee()){
            $orQuery->add('s.dateCloture < :dateDuJour');
        }

        $qb->andWhere($orQuery);
            if($orQuery && $filter->getPassee()) {
                $qb->setParameter('dateDuJour', $date = new \DateTime());
            };
            if($filter->getIsOrganisateur() || $filter->getIsInscrit() || $filter->getIsNotInscrit()){
                $qb->setParameter('user', $user->getPseudo());
            }


        $query = $qb->getQuery();
        return new Paginator($query);
    }
}
