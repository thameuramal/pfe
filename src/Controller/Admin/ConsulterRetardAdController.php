<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Societes;
use App\Document\ParametreGenerale; 
use App\Document\Employe;
use App\Document\Retard; 
use App\Document\ChektTimeIn;
use App\Document\ChektTimeOut; 
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class ConsulterRetardAdController extends AbstractController
{
    /**
     * * @IsGranted("ROLE_ADMIN")
     * @Route("/delay/time", name="delaytime")
     */
    public function index(DocumentManager $dm,Request $request): Response 
    {  $heures = array();
        $input=array();
        $tt=array();
        $listedateersss=array();
        $listedateerss=array();
        $listsemaine=array();
        $listejour=array();
        $infotimeretard=array();
        $jourdates=array();
        $listedatejour=array();
        // $count = $dm->createQueryBuilder('App\Document\HeureTravaille')->count()->getQuery()->execute();
        // if($count!=0){
        //     $dm->getSchemaManager()->dropDocumentCollection(Retard::class);
        //     $this->gettimeparjour($listedateersss,$dm);
        // }
        // else{
        //     $this->gettimeparjour($listedateersss,$dm);
        // }
        
        
       
        $listdate = $dm->getRepository(ChektTimeIn::class)->findby(array(),array('date'=>'asc'));
              ////////////////test liste
               foreach ($listdate as $lst)  {
        $listedateess[$lst->getIdEmp()][]=$lst->getDate();}
        foreach($listedateess as $k=>$v){
            $listedateerss=array_unique($v);
            // // echo $k;
            $listedateersss[]= array('id'=>$k,
            'liste'=>$listedateerss
        ); }
        $month = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
        // $count = $dm->createQueryBuilder('App\Document\HeureTravaille')->count()->getQuery()->execute();
        // if($count!=0){
        //     $dm->getSchemaManager()->dropDocumentCollection(Retard::class);
        //     $this->gettimeparjour($listedateersss,$dm);
        // }
        // else{
        //     $this->gettimeparjour($listedateersss,$dm);
        // }


        
   // recupere la date 
    // $timestamp = strtotime('26/01/2021');
    // $old_date = explode('/', '26/01/2021');
    // echo 'll'.$old_date[2];
    // echo 'll'.$old_date[1];
    // echo 'll'.$old_date[0];

    
foreach($listedateersss as $d){
            
    foreach($d['liste'] as $jourdate ){
        // echo 'tste'.$jourdate;
        $jour =  explode('/', $jourdate);;
                    
$mois= $jour[1].'/'.$jour[2];
array_push($listedatejour,$mois);

$jourdates=array_unique($listedatejour);

    }}
    foreach($jourdates as $dd){
        //  echo'dd'.$dd;
         foreach($listedateersss as $d){
            
            foreach($d['liste'] as $jourdate ){
                $jour =  explode('/', $jourdate);
                    
                $mois= $jour[1].'/'.$jour[2];
                if($mois== $dd){
                    $j=$jour[0];
                    $listejour[$mois][]=$j;
            }}}
    }

    foreach($listejour as $k=>$v){
        $listedateerss=array_unique($v);
           //echo'kk'.$k;
         
        $jour =  explode('/', $k);
        $monthNum  = $jour[0];
        setlocale(LC_ALL, 'french'); 
       // $monthName = strftime("%B", mktime(0, 0, 0, $monthNum, 10));
       $monthName=$month[$monthNum-1];
         $moisfinal=$monthName.' '.$jour[1];
        $listsemaine[]= array('mois'=>$moisfinal,
        
        'liste'=>$listedateerss
    ); 
}

// foreach($listsemaine as $kk){
//             // echo 'mois'.$kk['mois'];
//              foreach($kk['liste'] as $jourdate ){
//                 // echo 'dateeeee'.$jourdate;
//              }
//         }
  
            
             //liste  des heures par semaine ********************
             $societe =  $dm->getRepository(Societes::class)->findAll();
        foreach($societe as $societes){
        $employes = $dm->getRepository(Employe::class)->findBy(['societe' =>$societes->getNumero()]);
        foreach($employes as $employer){
            $retard = $dm->getRepository(Retard::class)->findBy(['idEmp'=>$employer->getIdemp()]);
                foreach($listsemaine as $kk){
                    
                    // echo 'mois'.$kk['mois'];
                    $hr='00:00:00';
                    // echo 'idemp'.$employer->getIdemp();
                    foreach($retard as $rd ){
                        
                        $jour =  explode('/', $rd->getJour());
                
                        $mois= $jour[1].'/'.$jour[2];
                    
                        $jour =  explode('/', $mois);
                        $monthNum  = $jour[0];
                        //setlocale(LC_ALL, 'french'); 
                        //$monthName = strftime("%B", mktime(0, 0, 0, $monthNum, 10));
                        $monthName=$month[$monthNum-1];
                        //echo 'mois'.$monthName;
                         $moisfinale=$monthName.' '.$jour[1];
                    if($moisfinale == $kk['mois']){
                    //echo 'jour'.$jour;
                    //echo 'date'.$rd->getNbrheure();
                    $secs=strtotime($rd->getNbrheure())-strtotime("00:00:00");
                    $herefinal=date("H:i:s",strtotime($hr)+$secs);
                    $hr=$herefinal ;}}
                     //echo 'hrr'.$hr; 
                    $heures[]=array('id'=>$employer->getIdemp(),
                    'heure'=>$hr, 'mois'=>$kk['mois']);
                
            
            }

           }
           $input [] =  array
           ('nome' => $societes->getNom(), 
          'employe' => $employes,
          'idsociete'=>$societes->getNumero(),
      
          'nbreheur'=>$heures
      
          
      );  
    }

//recupere donne a partir base pour affichage 
    $timeretard =$dm->getRepository(Retard::class)->findAll();

        foreach($timeretard as $time){
            
          $employes = $dm->getRepository(Employe::class)->findBy(['idemp' =>$time->getidEmp()]);
          $societes = $dm->getRepository(Societes::class)->findBy(['numero' =>$time->getNumrosociete()]);


          $jour =  explode('/', $time->getJour());
        $monthNum  = $jour[1];
        //setlocale(LC_ALL, 'french'); 
        //$monthName = strftime("%B", mktime(0, 0, 0, $monthNum, 10));
        $monthName=$month[$monthNum-1];
        $jourfinal=$jour[0].'  '.$monthName.' '.$jour[2];
        $infotimeretard [] =  array
        (
       'idemp' => $time->getIdEmp(),
       'date'=>$jourfinal,
       
       'temps'=>$time->getNbrheure(),
       'listeemp'=>$employes,
    'listesocietes'=> $societes
       
    
        
   );
        }
 
 




    //remplir base par retard par jour 
    //  $this->gettimeparjour($listedateersss,$dm);
     
         
        return $this->render('Admin/consulter_retard_ad/index.html.twig', [
            'controller_name' => 'ConsulterRetardAdController','liste'=>$input,'heurreatrdjour'=>$infotimeretard
        ]);
    }
    public function gettimeparjour($listedateersss,DocumentManager $dm){
        $employes = $dm->getRepository(Employe::class)->findAll();
        foreach($employes as $employer){
            foreach($listedateersss as $d){
            if($d['id' ]== $employer->getIdemp()){
                foreach($d['liste'] as $jour ){
                    $hr='00:00:00';
                    $listdate = $dm->getRepository(ChektTimeIn::class)->findby(array('idEmp' =>$employer->getIdemp(),'date' => $jour),array('temps'=>'ASC'),1,0);
                    foreach ($listdate as $lst)  {
                //    echo 'id'.$lst->getIdEmp();
                //    echo 'date'.$lst->getDate();
                //   echo 'temps'.$lst->getTemps();
                     $date1= strtotime($lst->getTemps());
                         
                     $date2 = strtotime('08:00:00');
                     if($date1>$date2){
                     $x=abs($date1-$date2);
                     $h=floor(abs($date1-$date2)/(60*60));
                     
                    
                     $m=floor(($x-$h*60*60)/60);
                     $s=floor($x-$h*60*60 - $m*60);
                       $herinstante=$h.':'.$m.':'.$s;
                       $secs=strtotime($herinstante)-strtotime("00:00:00");
                       $herefinal=date("H:i:s",strtotime($hr)+$secs);
                       $hr=$herefinal ;}
                       else{$hr='00:00:00';}
                       $nbrheure = new Retard();
                       $nbrheure-> setIdEmp($lst->getIdEmp());
                       $nbrheure-> setNumrosociete($lst->getNumeroSocietes());
                       $nbrheure-> setJour($jour);
                       $nbrheure-> setNbrheure($hr);
                       $dm->persist($nbrheure);
                       $dm->flush();
                  

                    }
                }
            }
        }}

    }
    public function gettimeparsemaine($listedateersss,$listsemaine,DocumentManager $dm){
        $societe =  $dm->getRepository(Societes::class)->findAll();
        foreach($societe as $societes){
        $employes = $dm->getRepository(Employe::class)->findBy(['societe' =>$societes->getNumero()]);
        foreach($employes as $employer){
            $retard = $dm->getRepository(Retard::class)->findBy(['idEmp'=>$employer->getIdemp()]);
                foreach($listsemaine as $kk){
                    
                  //  echo 'mois'.$kk['mois'];
                    $hr='00:00:00';
                  //  echo 'idemp'.$employer->getIdemp();
                    foreach($retard as $rd ){
                        
                        $jour =  explode('/', $rd->getJour());
                
                        $mois= $jour[1].'/'.$jour[2];
                    
                    if($mois == $kk['mois']){
                    //  foreach($kk['liste'] as $jourdate ){
                        
                    //     echo 'dateeeee'.$jourdate;
                    //  }
                    $secs=strtotime($rd->getNbrheure())-strtotime("00:00:00");
                    $herefinal=date("H:i:s",strtotime($hr)+$secs);
                    $hr=$herefinal ;}}
                    //echo 'hrr'.$hr; 
                    $heures[]=array('id'=>$employer->getIdemp(),
                    'heure'=>$hr, 'mois'=>$kk['mois']);
                
            
            }

           }
           $input [] =  array
           ('nome' => $societes->getNom(), 
          'employe' => $employes,
      
          'nbreheur'=>$heures
       
          
      );  
    }


}
}
