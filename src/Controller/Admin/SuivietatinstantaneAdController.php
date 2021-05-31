<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Societes;
 
use App\Document\Employe;

use App\Document\ChektTimeIn;
use App\Document\ChektTimeOut;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class SuivietatinstantaneAdController extends AbstractController
{
    /**
     * * @IsGranted("ROLE_ADMIN")
     * @Route("/tracking/presence/status", name="trackingpresencestatus")
     */
    public function index(DocumentManager $dm): Response
    { $etat='';
        $outtimein=array();
        $etatin=array();
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
 //echo date("H:i:s", $time); 
 //echo date("Y-m-d ", $time); 
 $format = 'd/m/Y';
 //echo date("H:i:s", $time); 
 //echo date($format, $time); 
 $dateday=date($format, $time);
$societe =  $dm->getRepository(Societes::class)->findAll();
foreach($societe as $societes){
    $numsociete= $societes->getNumero();
   
    $employes = $dm->getRepository(Employe::class)->findBy(['societe' =>$numsociete]);

    foreach($employes as $employer){
            
        $tmpout = $dm->getRepository(ChektTimeOut::class)->findBy(['idEmp' =>$employer->getIdemp(),'date'=>$dateday]);
        $tmpin = $dm->getRepository(ChektTimeIn::class)->findBy(['idEmp' =>$employer->getIdemp(),'date'=>$dateday]);
       // echo'id'.$employer->getNom();
        foreach($tmpout as $out){
            //  echo'id'.$employer->getNom();
            //  echo 'tt'.$out->getTemps();
             if($out->getTemps()<='13:30:00'){
                foreach($tmpin as $in){
                    // echo 'id'.$in->getIdEmp();
                    // echo 'dateour'.$out->getTemps();
                    // echo 'datein'.$in->getTemps();
                    if($in->getTemps()>$out->getTemps())
                    {
                        // echo 'present'.$in->getIdEmp();
                    $etat='in';
                    } 
                    else 
                    {
                        // echo 'absant'.$in->getIdEmp();
                        $etat='out';
                    }
                }  
                
             }
            
                
               
        }
        // echo 'persone'.$employer->getNom();
        // echo 'etat'.$etat;

        if($etat == 'in'){
            $etatin[]= array (
                // 'tstliste'=>$listdateer,
                'id'=>$employer->getIdemp(),
                'etat'=>'in',
                'timeout'=>$tmpout,
                // 'timee'=>$datetst 
                
                  );
        }else{
            $outtimein[]= array (
                // 'tstliste'=>$listdateer,
                'id'=>$employer->getIdemp(),
                'etat'=>$etat,
                'timeout'=>$tmpout,
                // 'timee'=>$datetst 
                
                  );
        }
        
        
    
    }
    $input [] =  array
    ('nome' => $societes->getNom(), 
   'employe' => $employes, 
   // 'date'=>$datearray
   //  'tmps'=>$outemps
   // 'idd'=>$iid,
   // 'tmps'=>$outtime,
   'etatin'=>$etatin,
   'tmpsout'=>$outtimein);

}
// foreach($input as $t){
//     echo 'societ'.$t['nome'];
//     foreach($t['etatin'] as $etat){
// echo 'id'.$etat['id'];
// echo 'eta'.'in';
//     }
//     foreach($t['tmpsout'] as $etatt){
//         // echo 'id'.$etatt['id'];
//         // echo 'eta'.'in';
//             }
// }
 
        return $this->render('Admin/suivietatinstantane_ad/index.html.twig', [
            'controller_name' => 'SuivietatinstantaneAdController','info'=>$input, 'date'=>$dateday
        ]);
    }
}
