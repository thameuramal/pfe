<?php

namespace App\Controller\RH;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiqueRhController extends AbstractController
{
    /**
     * @Route("/statistique/rh", name="statistique_rh")
     */
    public function index(): Response
    {$user = $this->getUser();
        echo $user->getNom();
        return $this->render('RH/statistique_rh/index.html.twig', [
            'controller_name' => 'StatistiqueRhController',
        ]);
    }
}
