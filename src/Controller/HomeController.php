<?php

namespace App\Controller;

use App\Entity\Images;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TrickRepository $repo)
    {
        $images = new Images;
        $tricks = $repo->findAll();
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'tricks' => $tricks,
            'images' => $images
        ]);
    }
}
