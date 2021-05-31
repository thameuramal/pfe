<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsulterAbsenceAdController extends AbstractController
{
    /**
     * @Route("/consulter/absence/ad", name="consulter_absence_ad")
     */
    public function index(): Response
    {
        return $this->render('Admin/consulter_absence_ad/index.html.twig', [
            'controller_name' => 'ConsulterAbsenceAdController',
        ]);
    }
}
