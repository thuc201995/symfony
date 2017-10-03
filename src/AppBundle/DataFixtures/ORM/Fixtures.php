<?php

namespace AppBundle\DataFixtures\ORM;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        
            $user = new User();
            $user->setUsername('admin');
            $user->setPassword('test');
            $manager->persist($user);
      

        $manager->flush();
    }
}