<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantControlerController extends AbstractController
{
    /**
     * @Route("/participant/controler", name="profile",)
     */
    public function profile()
    {
        return $this->render('participant_controler/profile.html.twig', [
            'controller_name' => 'ParticipantControlerController',
        ]);
    }
}
