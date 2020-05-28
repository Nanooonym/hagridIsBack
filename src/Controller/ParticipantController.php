<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/participant")
 */
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
     * @Route("/{id}", name="participant_show", requirements={"id": "\d+"})
     * @param int $id
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function show(int $id, EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Participant::class);
        $participant = $repo->find($id);
        return $this->render('participant/show.html.twig', ["participant" => $participant]);
    }

    /**
     * @Route("/admin/new", name="participant_new", methods={"GET","POST"})
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
     * @Route("/admin", name="participant_index")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Participant::class);
        $participants=$repository->findAll();
        return $this->render('participant/index.html.twig', [
            'participants' => $participants
        ]);
    }

    /**
     * @Route("/{id}/edit", name="participant_edit", methods={"GET","POST"})
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
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
               "participant" => $participant,
                "participantForm" => $participantForm->createView(),
            ]);
    }

    /**
     * @Route("/admin/{id}/desactiver", name="participant_desactiver", methods={"GET","POST"})
     */
    public function desactiver(Request $request, Participant $participant): Response
    {
        $actif = $participant->getActif();
        if($actif){
            $participant->setActif(false);
            $this->addFlash('success', $participant->getPseudo() . '" est maintenant inactif');
        }else{
            $participant->setActif(true);
            $this->addFlash('success', $participant->getPseudo() . '" est maintenant actif');
        }

        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('participant.home');
    }

    /**
     * @Route("/admin/{id}/role", name="participant_role", methods={"GET","POST"})
     */
    public function role(Request $request, Participant $participant): Response
    {
/*        dump(in_array('ROLE_ADMIN', $participant->checkRoles()));
        die();*/


        if(in_array('ROLE_ADMIN', $participant->checkRoles())){
            $participant->setRoles(array('ROLE_USER'));
            $this->addFlash('success', $participant->getPseudo() . ' n\'est plus administrateur');
        }else{
            $participant->setRoles(array('ROLE_ADMIN'));
            $this->addFlash('success', $participant->getPseudo() . ' est maintenant administrateur');
        }

        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('participant.home');
    }


}
