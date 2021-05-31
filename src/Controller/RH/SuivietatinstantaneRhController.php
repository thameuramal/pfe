<?php

namespace App\Controller\RH;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuivietatinstantaneRhController extends AbstractController
{
    /**
     * @Route("/suivietatinstantane/rh", name="suivietatinstantane_rh")
     */
    public function index(): Response
    {
        return $this->render('RH/suivietatinstantane_rh/index.html.twig', [
            'controller_name' => 'SuivietatinstantaneRhController',
        ]);
    }
}
