<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Entity\SortieFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

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
            $qb->andWhere('s.dateDebut < :dateDebut')
                ->setParameter('dateDebut', $filter->getDateDebut());
        }

        if($filter->getDateFin() || $filter->getDateFin() != null){
            $qb->andWhere('s.dateDebut > :dateCloture')
                ->setParameter('dateCloture', $filter->getDateFin());
        }

        if($filter->getIsInscrit() == true){
            $qb->leftJoin('s.participants', 'p');
            $qb->addSelect('p');

            $qb->orWhere('p.pseudo LIKE :user')
                ->setParameter('user', $user->getPseudo());
        }

        if($filter->getIsNotInscrit()){

            $qb->orWhere('s.participants is EMPTY');

            if(!$filter->getIsInscrit()){
                $qb->leftJoin('s.participants', 'p');
                $qb->addSelect('p');
            }

            $qb->orWhere('p.pseudo NOT LIKE :user')
                ->setParameter('user', $user->getPseudo());
        }

        if($filter->getIsOrganisateur()) {
            $qb->leftJoin('s.organisateur', 'o');
            $qb->addSelect('o');

            $qb->orWhere('o.pseudo LIKE :user')
                ->setParameter('user', $user->getPseudo());

        }

        if($filter->getPassee()) {

            $qb->orWhere('s.dateCloture < :dateDuJour')
                ->setParameter('dateDuJour', $date = new \DateTime());

        }

        $query = $qb->getQuery();
        dump($query);
        return new Paginator($query);
    }
}
