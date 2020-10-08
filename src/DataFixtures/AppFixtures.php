<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\User;
use App\Entity\Images;
use App\Entity\Videos;


class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User;
        $password = $this->encoder->encodePassword($user, 'toto');
        $user->setPseudo('toto')
             ->setMail('toto@toto.com')
             ->setPassword($password)
             ->setAvatar('');

             $manager->persist($user);

        $user1 = new User;
        $password = $this->encoder->encodePassword($user, 'tata');
        $user1->setPseudo('tata')
             ->setMail('tata@tata.com')
             ->setPassword($password)
             ->setAvatar('');

             $manager->persist($user1);

             $user2 = new User;
             $password = $this->encoder->encodePassword($user, 'tutu');
             $user2->setPseudo('tutu')
                  ->setMail('tutu@tutu.com')
                  ->setPassword($password)
                  ->setAvatar('');
     
                  $manager->persist($user1);

        $image = new Images;

        $videos = new Videos;

        $category1 = new Category();
        $category1->setCategory("Grabs");
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setCategory("Rotations");
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setCategory("Straight Airs");
        $manager->persist($category3);

        $category4 = new Category();
        $category4->setCategory("Old School");
        $manager->persist($category4);

            

            for($a = 1 ; $a<=5; $a++) {
                $trick = new trick();
                $trick->setName("
                trick n°$a")
                ->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris mi nibh, ultricies et odio eu, suscipit accumsan ligula. Donec ut elit tortor. Nullam non placerat tellus, ac tempus ipsum. Quisque venenatis metus non eros congue, gravida fermentum leo aliquet. Morbi eget dolor eget purus tincidunt efficitur a non sem. Interdum et malesuada fames ac ante ipsum primis in faucibus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Duis nec urna eget lorem consectetur varius convallis in nisl. Aliquam luctus augue non sapien pulvinar accumsan. Quisque non arcu suscipit, efficitur sapien sit amet, iaculis odio. Nulla aliquam risus molestie, semper felis sit amet, dignissim ante. In maximus magna vitae quam ullamcorper consectetur. Cras malesuada et justo eu finibus. Nunc sodales gravida erat. Nunc eget feugiat nibh, et dapibus neque. Donec sagittis metus id congue condimentum. Morbi vitae eros quis erat pellentesque malesuada. Fusce a lectus nec augue euismod efficitur. Phasellus condimentum ante dignissim, tempor massa non, fermentum felis. Pellentesque et sapien in odio imperdiet auctor. In hac habitasse platea dictumst. Suspendisse nulla quam, placerat quis volutpat vitae, volutpat a nulla. Donec molestie eros non arcu cursus pellentesque. Etiam non vulputate felis. Nam varius commodo neque, at molestie elit iaculis sit amet. Proin id quam turpis. Praesent molestie felis ut faucibus rhoncus. Interdum et malesuada fames ac ante ipsum primis in faucibus.")
                ->setCreationDate(new \DateTime())
                ->setCategory($category2)
                ->setUser($user);

                $manager->persist($trick);

                for($e = 1 ; $e<=5; $e++) {
                    $comment = new Comment;
                    $comment->setTrick($trick)
                            ->setContent('Sed aliquet pharetra velit sed rhoncus. Mauris sit amet tincidunt nisl, porttitor fermentum arcu. Ut aliquam ac ligula vel faucibus.')
                            ->setCreationDate(new \DateTime())
                            ->setUser($user2);

                    $manager->persist($comment);
                }

            }   
            
            for($b = 1 ; $b<=5; $b++) {
                $trick = new trick();
                $trick->setName("
                trick n°$b")
                ->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris mi nibh, ultricies et odio eu, suscipit accumsan ligula. Donec ut elit tortor. Nullam non placerat tellus, ac tempus ipsum. Quisque venenatis metus non eros congue, gravida fermentum leo aliquet. Morbi eget dolor eget purus tincidunt efficitur a non sem. Interdum et malesuada fames ac ante ipsum primis in faucibus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Duis nec urna eget lorem consectetur varius convallis in nisl. Aliquam luctus augue non sapien pulvinar accumsan. Quisque non arcu suscipit, efficitur sapien sit amet, iaculis odio. Nulla aliquam risus molestie, semper felis sit amet, dignissim ante. In maximus magna vitae quam ullamcorper consectetur. Cras malesuada et justo eu finibus. Nunc sodales gravida erat. Nunc eget feugiat nibh, et dapibus neque. Donec sagittis metus id congue condimentum. Morbi vitae eros quis erat pellentesque malesuada. Fusce a lectus nec augue euismod efficitur. Phasellus condimentum ante dignissim, tempor massa non, fermentum felis. Pellentesque et sapien in odio imperdiet auctor. In hac habitasse platea dictumst. Suspendisse nulla quam, placerat quis volutpat vitae, volutpat a nulla. Donec molestie eros non arcu cursus pellentesque. Etiam non vulputate felis. Nam varius commodo neque, at molestie elit iaculis sit amet. Proin id quam turpis. Praesent molestie felis ut faucibus rhoncus. Interdum et malesuada fames ac ante ipsum primis in faucibus.")
                ->setCreationDate(new \DateTime())
                ->setCategory($category3)
                ->setUser($user1);

                $manager->persist($trick);

                for($f = 1 ; $f<=5; $f++) {
                    $comment = new Comment;
                    $comment->setTrick($trick)
                            ->setContent('Donec pretium dolor et sapien dictum pharetra a quis quam.')
                            ->setCreationDate(new \DateTime())
                            ->setUser($user);
                            
                    $manager->persist($comment);
                }
            }   

            for($c = 1 ; $c<=5; $c++) {
                $trick = new trick();
                $trick->setName("
                trick n°$c")
                ->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris mi nibh, ultricies et odio eu, suscipit accumsan ligula. Donec ut elit tortor. Nullam non placerat tellus, ac tempus ipsum. Quisque venenatis metus non eros congue, gravida fermentum leo aliquet. Morbi eget dolor eget purus tincidunt efficitur a non sem. Interdum et malesuada fames ac ante ipsum primis in faucibus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Duis nec urna eget lorem consectetur varius convallis in nisl. Aliquam luctus augue non sapien pulvinar accumsan. Quisque non arcu suscipit, efficitur sapien sit amet, iaculis odio. Nulla aliquam risus molestie, semper felis sit amet, dignissim ante. In maximus magna vitae quam ullamcorper consectetur. Cras malesuada et justo eu finibus. Nunc sodales gravida erat. Nunc eget feugiat nibh, et dapibus neque. Donec sagittis metus id congue condimentum. Morbi vitae eros quis erat pellentesque malesuada. Fusce a lectus nec augue euismod efficitur. Phasellus condimentum ante dignissim, tempor massa non, fermentum felis. Pellentesque et sapien in odio imperdiet auctor. In hac habitasse platea dictumst. Suspendisse nulla quam, placerat quis volutpat vitae, volutpat a nulla. Donec molestie eros non arcu cursus pellentesque. Etiam non vulputate felis. Nam varius commodo neque, at molestie elit iaculis sit amet. Proin id quam turpis. Praesent molestie felis ut faucibus rhoncus. Interdum et malesuada fames ac ante ipsum primis in faucibus.")
                ->setCreationDate(new \DateTime())
                ->setCategory($category1)
                ->setUser($user2);

                $manager->persist($trick);

                for($g = 1 ; $g<=5; $g++) {
                    $comment = new Comment;
                    $comment->setTrick($trick)
                            ->setContent('Ut in purus mi. Praesent eget sollicitudin arcu, at faucibus ipsum')
                            ->setCreationDate(new \DateTime())
                            ->setUser($user);

                    $manager->persist($comment);
                }
            }
            
            for($d = 1 ; $d<=3; $d++) {
                $trick = new trick();
                $trick->setName("
                trick n°$d")
                ->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris mi nibh, ultricies et odio eu, suscipit accumsan ligula. Donec ut elit tortor. Nullam non placerat tellus, ac tempus ipsum. Quisque venenatis metus non eros congue, gravida fermentum leo aliquet. Morbi eget dolor eget purus tincidunt efficitur a non sem. Interdum et malesuada fames ac ante ipsum primis in faucibus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Duis nec urna eget lorem consectetur varius convallis in nisl. Aliquam luctus augue non sapien pulvinar accumsan. Quisque non arcu suscipit, efficitur sapien sit amet, iaculis odio. Nulla aliquam risus molestie, semper felis sit amet, dignissim ante. In maximus magna vitae quam ullamcorper consectetur. Cras malesuada et justo eu finibus. Nunc sodales gravida erat. Nunc eget feugiat nibh, et dapibus neque. Donec sagittis metus id congue condimentum. Morbi vitae eros quis erat pellentesque malesuada. Fusce a lectus nec augue euismod efficitur. Phasellus condimentum ante dignissim, tempor massa non, fermentum felis. Pellentesque et sapien in odio imperdiet auctor. In hac habitasse platea dictumst. Suspendisse nulla quam, placerat quis volutpat vitae, volutpat a nulla. Donec molestie eros non arcu cursus pellentesque. Etiam non vulputate felis. Nam varius commodo neque, at molestie elit iaculis sit amet. Proin id quam turpis. Praesent molestie felis ut faucibus rhoncus. Interdum et malesuada fames ac ante ipsum primis in faucibus.")
                ->setCreationDate(new \DateTime())
                ->setCategory($category4)
                ->setUser($user1);

                $manager->persist($trick);

                for($h = 1 ; $h<=5; $h++) {
                    $comment = new Comment;
                    $comment->setTrick($trick)
                            ->setContent('Proin vel ex faucibus, elementum est in, tincidunt ipsum.')
                            ->setCreationDate(new \DateTime())
                            ->setUser($user);

                    $manager->persist($comment);
                }
            }   
        
        $manager->flush();
    }
}
