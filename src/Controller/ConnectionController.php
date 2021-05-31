<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\User;
use App\Document\Societes;
use App\Document\ChektTimeOut;
use App\Document\ChektTimeIn;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ConnectionController extends AbstractController
{
     
 
    public function connect()
   {
     $dataBaseName = $this->getParameter('namedbacces');
     $conn=odbc_connect($dataBaseName,"","");
       return $conn;
    }
    //founction pour ajouter table des entreprises
      /**
     * @Route("/defaultt", name="defaultt")
     */
    public function ajouterSocietes(DocumentManager $dm){
        $cnx =$this->connect();
        $sql = "SELECT * FROM DEPARTMENTS";
      $rs = odbc_exec($cnx,$sql);
      
      while(odbc_fetch_row($rs)){
        $nom =odbc_result($rs,'DEPTNAME');
        $numro =odbc_result($rs,'DEPTID');
        echo"id".$numro;
        echo"name".$nom;
        echo"<br/>";
        $Societs = new Societes();
        $Societs ->setNom($nom);
        $Societs ->setNumero($numro);
        $dm->persist($Societs);
        $dm->flush();
        
       }
       
       return new Response('Created product id '.$Societs->getId());
    }
    /**
     * @Route("/default", name="default")
     */
   
    public function createAction(DocumentManager $dm)
    {
      $results = $dm->getRepository(ChektTimeIn::class)->findBy(array(),array('id'=>'DESC'),1,0);
       $date=$results[0]->getTemps();
      //  $lastdatein = strtotime($date);
      //   $datelast= date('d/m/Y H:i:s', $lastdatein);
       echo"date".$date;
        // $this->ajouterSocietes();
        //$dataBaseName = $this->getParameter('namedbacces');
        //$conn=odbc_connect($dataBaseName,"","");
        $cx =$this->connect();
// ---------------******************----------------------
        $sqll="SELECT * FROM  CHECKINOUT where e_flag='I'  ORDER BY CHECKTIME DESC"; 
        $rs = odbc_exec($cx,$sqll);
        while(odbc_fetch_row($rs)){
          $namet =odbc_result($rs,'CHECKTIME');
          $datee = strtotime($namet);
           $tempsin= date('d/m/Y H:i:s', $datee);
          if($date != $namet){
          echo"name".$namet;
          echo"<br/>";
          }
          else break;   
      
       }

        $sql = "SELECT * FROM DEPARTMENTS";
      $rs = odbc_exec($cx,$sql);
      
       while(odbc_fetch_row($rs)){
          $name =odbc_result($rs,'DEPTNAME');
          echo"name".$name;
          echo"<br/>";
       }
        $user = new User();
        $user->setDb('A Foo Bar');
       
    
        $dm->persist($user);
        $dm->flush();
    
        return new Response('Created product id '.$user->getId());
    }
}
