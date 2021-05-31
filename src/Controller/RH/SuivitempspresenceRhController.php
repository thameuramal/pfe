<?php

namespace App\Controller\RH;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuivitempspresenceRhController extends AbstractController
{
    /**
     * @Route("/suivitempspresence/rh", name="suivitempspresence_rh")
     */
    public function index(): Response
    {
        return $this->render('RH/suivitempspresence_rh/index.html.twig', [
            'controller_name' => 'SuivitempspresenceRhController',
        ]);
    }
}
