<?php

namespace App\Controller\Emp;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ConsulterAlertEmpController extends AbstractController
{ private $security;
    public function __construct(Security $security)
    {
       $this->security = $security;
    }
    /**
     * @Route("/consulter/alert/emp", name="consulter_alert_emp",requirements={"name"=".+"})
     */
    public function index(): Response
    { 
        $user = $this->security->getUser();
        echo 'tt'.$user->getId();
      
        return $this->render('Emp/consulter_alert_emp/index.html.twig', [
            'controller_name' => 'ConsulterAlertEmpController',
        ]);
    }
}
