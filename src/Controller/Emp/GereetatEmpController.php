<?php

namespace App\Controller\Emp;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GereetatEmpController extends AbstractController
{
    /**
     * @Route("/gereetat/emp", name="gereetat_emp")
     */
    public function index(): Response
    {
        return $this->render('Emp/gereetat_emp/index.html.twig', [
            'controller_name' => 'GereetatEmpController',
        ]);
    }
}
