<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/user/home", name="home")
     */
    public function home()
    {
        return $this->render('user/home.html.twig');
    }

    /**
     * @Route("/initialize", name="initialize")
     */
    public function initializeDatas(EntityManagerInterface $em)
    {
        $ville = new Ville();
        $ville->setNom("Rennes");
        $ville->setCodePostal("35000");
        $em->persist($ville);

        $lieu = new Lieu();
        $lieu->setNom("ChezMoi");
        $lieu->setRue("Rue Jean Marin");
        $lieu->setLatitude("25552.3");
        $lieu->setLongitude("30225.5");
        $lieu->setVille($ville);
        $em->persist($lieu);

        $campus = new Campus();
        $campus->setNom("Rennes 2");
        $em->persist($campus);

        $participant = new Participant();
        $participant->setNom("Quenet");
        $participant->setPseudo("Nanonym");
        $participant->setActif(true);
        $participant->setAdministrateur(false);
        $participant->setCampus($campus);
        $participant->setMail("test@test.fr");
        $participant->setMotDePasse("test");
        $participant->setPrenom("Paul");
        $participant->setTelephone("321654987");
        $em->persist($participant);

        $etat = new Etat();
        $etat->setLibelle("En Cours");
        $em->persist($etat);

        $sortie = new Sortie();
        $sortie->setCampus($campus);
        $sortie->setNom("En Sort");
        $sortie->setDateCloture(new \DateTime());
        $sortie->setDateDebut(new \DateTime());
        $sortie->setEtat($etat);
        $sortie->setLieu($lieu);
        $sortie->setNbInscriptionsMax(8);
        $sortie->setOrganisateur($participant);
        $em->persist($sortie);

        $em->flush();
    }
}
