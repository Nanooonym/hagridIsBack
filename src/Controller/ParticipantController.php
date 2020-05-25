<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/participant/{id}", name="profile", requirements={"id": "\d+"})
     * @param int $id
     * @param EntityManagerInterface $em
     * @return Response
     * @Route("/participant/{id}", name="profile", requirements={"id": "\d+"})
     */
    public function profile(int $id, EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Participant::class);
        $participant = $repo->find($id);
        return $this->render('participant/profile.html.twig', ["participant" => $participant]);
    }

    /**
     * @Route("/participant/new", name="participant_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $participant = new Participant();
        $participantForm = $this->createForm(ParticipantType::class, $participant);
        $participantForm->handleRequest($request);

        if ($participantForm->isSubmitted() && $participantForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $participant->setAdministrateur(false);
            $participant->setActif(true);
            $motDePasse = $passwordEncoder->encodePassword($participant, $participant->getMotDePasse());
            $participant->setMotDePasse($motDePasse);
            $em->persist($participant);
            $em->flush();

            return $this->redirectToRoute('sortie_index');
        }

        return $this->render('participant/new.html.twig', [
            'participant' => $participant,
            'participantForm' => $participantForm->createView(),
        ]);
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

    /**
     * @Route("/participant/{id}/edit", name="participant_edit", methods={"GET","POST"})
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function edit(EntityManagerInterface $em, Request $request, Participant $participant, $id,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $participant = new Participant();
        $participantRepo = $this->getDoctrine()->getRepository(participant::class);
        $participant = $participantRepo->find($id);
        $participantForm = $this->createForm(ParticipantType::class, $participant);
        $participantForm->handleRequest($request);

        if ($participantForm->isSubmitted() && $participantForm->isValid()) {
            $motDePasse = $passwordEncoder->encodePassword($participant, $participant->getMotDePasse());
            $participant->setMotDePasse($motDePasse);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('participant_edit', [
                'id' => $participant->getId()
            ]);
        }
           return $this->render('participant/edit.html.twig', [
                "participantForm" => $participantForm->createView(),
            ]);
    }

}
