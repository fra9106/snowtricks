<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Trick;
use App\Entity\Category;
use App\Entity\User;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1 ; $i<=10; $i++) {
            $trick = new trick();
            $category = new Category;
            $user = new User;
        $category->setCategory(1);
        $user->setPseudo(1);
        $trick->setName("Nom du trick n°$i")
                  ->setDescription("contenu du trick n°$i")
                  ->setCreationDate(new \DateTime())
                  ->setCategory($category)
                  ->setUser($user);
                  
        $manager->persist($trick);
        }
        

        $manager->flush();
    }
}
