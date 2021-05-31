<?php

namespace App\Controller\RH;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsulterprofilpersonnelRhController extends AbstractController
{
    /**
     * @Route("/consulterprofilpersonnel/rh", name="consulterprofilpersonnel_rh")
     */
    public function index(): Response
    {
        return $this->render('RH/consulterprofilpersonnel_rh/index.html.twig', [
            'controller_name' => 'ConsulterprofilpersonnelRhController',
        ]);
    }
}
