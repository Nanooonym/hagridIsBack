<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @var ParticipantRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ParticipantRepository $repository, EntityManagerInterface $em)
    {
        $this->repository=$repository;
        $this->em=$em;
    }

    /**
     * @Route("/participant", name="participant.home")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function home(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Participant::class);
        $participants=$repository->findAll();
        return $this->render('user/home.html.twig');
    }

}
