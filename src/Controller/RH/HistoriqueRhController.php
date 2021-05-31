<?php

namespace App\Controller\RH;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoriqueRhController extends AbstractController
{
    /**
     * @Route("/historique/rh", name="historique_rh")
     */
    public function index(): Response
    {$user = $this->getUser();
        echo $user->getNom();
        return $this->render('RH/historique_rh/index.html.twig', [
            'controller_name' => 'HistoriqueRhController',
        ]);
    }
}
