<?php

namespace App\Controller\RH;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsulterprofisRhController extends AbstractController
{
    /**
     * @Route("/consulterprofis/rh", name="consulterprofis_rh")
     */
    public function index(): Response
    {
        return $this->render('RH/consulterprofis_rh/index.html.twig', [
            'controller_name' => 'ConsulterprofisRhController',
        ]);
    }
}
