<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
<<<<<<< HEAD
use Doctrine\Persistence\ObjectManager;
=======
use Doctrine\Common\Persistence\ObjectManager;
>>>>>>> origin/master

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
