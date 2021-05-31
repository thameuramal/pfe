<?php

namespace App\Controller\Admin;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Societes;
 
use App\Document\Employe;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Document\ChektTimeIn;
use App\Document\ChektTimeOut;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuivitempspresenceAdController extends AbstractController
{
    /**
     * * @IsGranted("ROLE_ADMIN")
     * @Route("/instant/time/tracking", name="instanttimetracking")
     */
    public function index(DocumentManager $dm): Response
    {    
        
        $outtimein=array();
        $input= array();
//         $now = new DateTime();
//         $today = date("Y-m-d H:i:s"); 
//  echo  date("h:i:sa");

//  date_default_timezone_set('Africa/Tunis');
//   $date = date('d-m-y h:i:s');
//   echo $date;
//*********************** */ date today *********************************
date_default_timezone_set('Africa/Tunis');
 $time = gmmktime();
 $format = 'd/m/Y';
 //echo date("H:i:s", $time); 
 //echo date($format, $time); 
 $dateday=date($format, $time);
$societe =  $dm->getRepository(Societes::class)->findAll(); 
foreach($societe as $societes){
    $numsociete= $societes->getNumero();
   
    $employes = $dm->getRepository(Employe::class)->findBy(['societe' =>$numsociete]);

    foreach($employes as $employer){
      
        $tmpout = $dm->getRepository(ChektTimeOut::class)->findBy(['idEmp' =>$employer->getIdemp() , 'date'=>$dateday],array('temps' => 'asc'));
       

        $tmp = $dm->getRepository(ChektTimeIn::class)->findBy(['idEmp' =>$employer->getIdemp(),'date'=>$dateday],array('temps' => 'asc'));
        $outtimein[]= array (
            // 'tstliste'=>$listdateer,
            'id'=>$employer->getIdemp(),
            'timein'=>$tmp,
            'timeout'=>$tmpout,
            // 'timee'=>$datetst
            
              );
    
    }
    $input [] =  array
    ('nome' => $societes->getNom(), 
   'employe' => $employes, 
   // 'date'=>$datearray
   //  'tmps'=>$outemps
   // 'idd'=>$iid,
   // 'tmps'=>$outtime,
   'tmpsout'=>$outtimein);}
        return $this->render('Admin/suivitempspresence_ad/index.html.twig', [
            'controller_name' => 'SuivitempspresenceAdController','info'=>$input,'date'=>$dateday
        ]);
    }
}
