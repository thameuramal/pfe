<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;



class ConsulterprofisAdController extends AbstractController
{
    /**
     * @Route("/consulterprofis/ad", name="consulterprofis_ad")
     */
    public function index(): Response
    {
        return $this->render('Admin/consulterprofis_ad/index.html.twig', [
            'controller_name' => 'ConsulterprofisAdController',
        ]);
    }
     /**
     * @Route("/homecnxx", name="homecnx")
     * @param  Connection $conn
     * @return JsonResponse
     */
    public function indexe(Connection $conn): JsonResponse
    {
        $db="C:\Users\starinfo\Desktop\ATT2021.mdb";
        $conn = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=".$db.";Uid=; Pwd=;");
        $queryBuilder = $conn->createQueryBuilder();
        $data = $queryBuilder->select('*')->from('DEPARTEMENTS')->execute()->fetchAll();

        return $this->json([
            'data' => $data
        ]);
    }
}
