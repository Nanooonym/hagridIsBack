<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
}
