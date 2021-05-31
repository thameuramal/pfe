<?php

namespace App\Controller\RH;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsulterRetardRhController extends AbstractController
{
    /**
     * @Route("/consulter/retard/rh", name="consulter_retard_rh")
     */
    public function index(): Response
    {
        return $this->render('RH/consulter_retard_rh/index.html.twig', [
            'controller_name' => 'ConsulterRetardRhController',
        ]);
    }
}
