<?php

namespace App\Controller\RH;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HeureTravailRhController extends AbstractController
{
    /**
     * @Route("/heure/travail/rh", name="heure_travail_rh")
     */
    public function index(): Response
    {
        return $this->render('RH/heure_travail_rh/index.html.twig', [
            'controller_name' => 'HeureTravailRhController',
        ]);
    }
}
