<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Document\Employe;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class StatistiqueAdController extends AbstractController
{//Route("/admin", name="statistique_ad")
    /**
     *  * @IsGranted("ROLE_ADMIN")
     * @Route("/statstique", name="statistique_ad")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        //echo $user->getNom();
        return $this->render('Admin/statistique_ad/index.html.twig', [
            'controller_name' => 'StatistiqueAdController',
        ]);
    }
}
