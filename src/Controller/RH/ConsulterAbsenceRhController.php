<?php

namespace App\Controller\RH;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsulterAbsenceRhController extends AbstractController
{
    /**
     * @Route("/consulter/absence/rh", name="consulter_absence_rh")
     */
    public function index(): Response
    {
        return $this->render('RH/consulter_absence_rh/index.html.twig', [
            'controller_name' => 'ConsulterAbsenceRhController',
        ]);
    }
}
