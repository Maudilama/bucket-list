<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class mainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(): Response
    {
        return $this->render('main/home.html.twig');
    }

    /**
     * @Route("/about", name="main_about")
     */

    public function about(): Response
    {
        $rawData = file_get_contents("../data/team.json");
        $teamMembers = json_decode($rawData, true);

        return $this->render('main/about.html.twig', [
            'teamMembers' => $teamMembers
        ]);
    }

}