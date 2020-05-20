<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ParticipantRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant/{id}", name="profile", requirements={"id": "\d+"})
     */
    public function profile(int $id, EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Participant::class);
        $participant = $repo->find($id);
        return $this->render('participant/profile.html.twig', ["participant" => $participant]);
    }

    /**
     * @Route("/participant/insertParticipant", name="insertParticipant")
     */
    public function insertParticipant(EntityManagerInterface $em)
    {
        $campus = new Campus();
        $campus->setNom("niort");
        $em->persist($campus);

       $participant = new Participant();
        $participant->setPseudo("queri");
        $participant->setNom("Aguirre");
        $participant->setPrenom("Carlos");
        $participant->setTelephone("06 56 47 32");
        $participant->setMail("agicar@mail.com");
        $participant->setMotDePasse(321);
        $participant->setAdministrateur(false);
        $participant->setActif(true);
        $participant->setCampus($campus);

        $em->persist($participant);
        $em->flush();
        return $this->render("participant/add.html.twig", []);




    }

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
     * @Route("/unParticipant", name="unParticipant")
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function unParticipant(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $participant = new Participant();
        $participant->setPseudo('loulou');
        $participant->setNom('Lou');
        $participant->setPrenom('loulou');
        $participant->setTelephone('06 02 03 04 05');
        $participant->setMail('loulou@toto.com');
        $motDePasse = $passwordEncoder->encodePassword($participant, 'loulou');
        $participant->setMotDePasse($motDePasse);
        $participant->setAdministrateur(true);
        $participant->setActif(true);
        $campus = new Campus();
        $campus->setNom('Chartre-de-Bretagne');
        $em->persist($campus);
        $participant->setCampus($campus);
        $em->persist($participant);
        $em->flush();

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
