<?php

namespace App\Controller;

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
}
