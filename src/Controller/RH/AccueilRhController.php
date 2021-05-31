<?php

namespace App\Controller\RH;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilRhController extends AbstractController
{
    /**
     * @Route("/accueil/rh", name="accueil_rh")
     */
    public function index(): Response
    {
        return $this->render('RH/accueil_rh/index.html.twig', [
            'controller_name' => 'AccueilRhController',
        ]);
    } 
  
}
