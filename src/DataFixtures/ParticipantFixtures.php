<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ParticipantFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $participant = new Participant();
        $participant->setPseudo('toto');
        $participant->setNom('ty');
        $participant->setPrenom('toto');
        $participant->setTelephone('01 02 03 04 05');
        $participant->setMail('toto@toto.com');
        $participant->setMotDePasse('toto');
        $participant->setAdministrateur(true);
        $participant->setActif(true);
        $campus = new Campus();
        $campus->setNom('Chartre-de-Bretagne');
        $manager->persist($campus);
        $participant->setCampus($campus);
        $manager->persist($participant);
        $manager->flush();
    }
}
