<?php

namespace  App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Validator\Constraints\DateTime;

use App\Document\Societes;
 
use App\Document\Employe;

use App\Document\ChektTimeIn;
use App\Document\ChektTimeOut;
use App\Document\HeureTravaille;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class HeureTravailController extends AbstractController
{
    /**
     * * @IsGranted("ROLE_ADMIN")
     * @Route("/working/time", name="workingtime")
     */
    public function index(DocumentManager $dm): Response

    { 
         $her=0;
        $min=0;
        $sec=0;
        $listedateersss=array();
        $listedateerss=array();
        $jourdates=array();
        $listedatejour=array();
        $listejour=array();
        $input=array();
        $heureparmois=array();
        $infotimetravaill=array();
        $month = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");

        // $count = $dm->createQueryBuilder('App\Document\HeureTravaille')->count()->getQuery()->execute();
        // if($count!=0){
        //     // if($count ==0){
               
          
        //    // echo 'not videe';
        //      $dm->getSchemaManager()->dropDocumentCollection(HeureTravaille::class);
          // $this->gettimeparjour($dm);
       
        // }
        // else{
        //     //echo 'vide';
        //     $this->gettimeparjour($dm);
          
        //      }

// liste date par jour 
$listdate = $dm->getRepository(ChektTimeIn::class)->findAll();

 foreach ($listdate as $lst)  {
$listedateess[$lst->getIdEmp()][]=$lst->getDate();}
foreach($listedateess as $k=>$v){
$listedateerss=array_unique($v);

$listedateersss[]= array('id'=>$k,
'liste'=>$listedateerss
); }

 //liste de date par mois 
foreach($listedateersss as $d){
   foreach($d['liste'] as $jourdate ){
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
                    //   echo'kk'.$k;
                    
                    $jour =  explode('/', $k);
                    $monthNum  = $jour[0];
                      
                   // $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
                   $monthName=$month[$monthNum-1];
                     $moisfinal=$monthName.' '.$jour[1];
                    $listsemaine[]= array('mois'=>$moisfinal,
                    
                    'liste'=>$listedateerss
                ); 
            }
                    //liste  des heures par mois ********************
                    $societe =  $dm->getRepository(Societes::class)->findAll();
                    foreach($societe as $societes){
                    $employes = $dm->getRepository(Employe::class)->findBy(['societe' =>$societes->getNumero()]);
                    foreach($employes as $employer){
                        $retard = $dm->getRepository(HeureTravaille::class)->findBy(['idEmp'=>$employer->getIdemp()]);
                            foreach($listsemaine as $kk){
                                
                                 //echo 'mois'.$kk['mois'];
                                $hr='00:00:00';
                                // echo 'idemp'.$employer->getIdemp();
                                foreach($retard as $rd ){
                                    
                                    $jour =  explode('/', $rd->getJour());
                            
                                    $mois= $jour[1].'/'.$jour[2];
                                    
                        $jour =  explode('/', $mois);
                        $monthNum  = $jour[0];
                         
                       // $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
                       $monthName=$month[$monthNum-1];
                         $moisfinale=$monthName.' '.$jour[1];
                          //echo 'date'.$moisfinale;
                                if($moisfinale == $kk['mois']){
                            //      echo 'date'.$moisfinale;
                              //    echo 'date1'.$kk['mois'];
                                $secs=strtotime($rd->getNbrheure())-strtotime("00:00:00");
                                $herefinal=date("H:i:s",strtotime($hr)+$secs);
                                $hr=$herefinal ;
                                 
                              }}
                                
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
        // //     foreach($combin as  $k=>$t)
        // //    {
            
        // //     foreach($t as  $kk){
        // //     $date1= strtotime($k);
        // // echo 'out'.$k;
        // //     $date2 = strtotime($kk);
        // //     echo 'out'.$kk;
        // //     $h=floor(abs($date1-$date2)/(60*60));
        // //     echo $h ;}

        // //    }           
            
        // }}
        // foreach($outputdate as $date=>$v){
        //     // echo'date'.$date;
        //     // foreach($v as $vv){
        //     // echo 'tst'.$vv;}
        // }
        // foreach($listedatee as $date){
        //     echo'date'.$date;
        //     echo 'tst'.$outputdate[$date];
        // }
        $date1= strtotime('11:34:06');
      
        $date2 = strtotime('7:59:07');
        
       
    //    echo 'tt'.$date1;
    //    echo 'ttin'.$date2;
        
      $h=floor(abs($date1-$date2)/(60*60));
    //    echo  'h'.floor(abs($date1-$date2)/(60*60));
       $x=abs($date1-$date2);
    //    echo  'm'.floor(($x-$h*60*60)/60);
       $m=floor(($x-$h*60*60)/60);
       $s=floor($x-$h*60*60 - $m*60);
    //    echo 's'.floor($x-$h*60*60 - $m*60);
       $her+=$h;
       $min+=$m;
       $sec+=$s;
       $hr1=$h.':'.$m.':'.$s;
       $secs=strtotime($hr1)-strtotime("00:00:00");
    //    echo'secs'.$secs;
    //    echo 'fin'.$her.$min.$sec;
    //    -----------------------------------
       $date12= strtotime('12:33:34');
      
       $date22 = strtotime('11:42:02');
       
      
   //    echo 'tt'.$date1;
   //    echo 'ttin'.$date2;
       
     $h=floor(abs($date12-$date22)/(60*60));
    //   echo  'h'.floor(abs($date12-$date22)/(60*60));
      $x=abs($date12-$date22);
    //   echo  'm'.floor(($x-$h*60*60)/60);
      $m=floor(($x-$h*60*60)/60);
      $s=floor($x-$h*60*60 - $m*60);
    //   echo 's'.floor($x-$h*60*60 - $m*60);
      $her+=$h;
      $min+=$m;
      $sec+=$s;
//  echo 'fin'.$her.$min.$sec;
 $hr=$h.':'.$m.':'.$s;
//  $hr=$her.':'.$min.':'.$sec;
//  echo 'nchlll'. date("H:i:s",strtotime($hr)+$secs);

//  $ntr=intval($hr);
//  echo'nbr'.$ntr;
//  echo 'tst'.intval($ntr%60);
//   echo 'total_minutes' . intval($ntr/60);
//   $total_minutes= intval($ntr/60);
//    echo '$minutes' .$total_minutes%60;
//    echo '$hours '.intval($ntr/60);
//  $dater = strtotime($ntr);
 
//  $daterr = strtotime('3:85:91');
 
//   $hh=floor($dater/(60*60));
//   echo'heur'.$hh;
//  $mm=floor( ($dater-$hh*60*60)/60);
//  echo'min'.$mm;
//  $ss=floor($dater-$hh*60*60 - $m*60);
//  echo's'.$ss.'termine';
    //   ----------------------------------
     

        // echo 'tst'. $dteDiff->format("%H:%I:%S");
        //  echo $date1-$date2->format('U') ;

        ///affiche les listes
    //     $societe =  $dm->getRepository(Societes::class)->findAll();
    //     foreach($societe as $societes){
    //         $numsociete= $societes->getNumero();
         
    //         $employes = $dm->getRepository(Employe::class)->findBy(['societe' =>$numsociete]);
    //         foreach($employes as $employer){
    //             $nbrheurs = $dm->getRepository(HeureTravaille::class)->findBy(['idEmp' =>$employer->getIdemp()]);
           
    //             $heures[]=array('id'=>$employer->getIdemp(),
    //                 'heure'=>$nbrheurs);
    //         }
    //         $input [] =  array
    //         ('nome' => $societes->getNom(), 
    //        'employe' => $employes,
       
    //        'nbreheur'=>$heures
    //     // 'nbreheur'=>$nbrheurs
           
    //    );
    //     } 
    $timeretard =$dm->getRepository(HeureTravaille::class)->findBy(array(),array('jour' => 'ASC'));

        foreach($timeretard as $time){
            
          $employes = $dm->getRepository(Employe::class)->findBy(['idemp' =>$time->getidEmp()]);
          $societes = $dm->getRepository(Societes::class)->findBy(['numero' =>$time->getNumrosociete()]);


          $jour =  explode('/', $time->getJour());
        $monthNum  = $jour[1];
        $monthName=$month[$monthNum-1];
       // $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
        $jourfinal=$jour[0].'  '.$monthName.' '.$jour[2];
        $infotimetravaill [] =  array
        (
       'idemp' => $time->getIdEmp(),
       'date'=>$jourfinal,
       
       'temps'=>$time->getNbrheure(),
       'listeemp'=>$employes,
    'listesocietes'=> $societes
       
    
        
   );
        }
     
        

        return $this->render('Admin/heure_travail/index.html.twig', [
            'controller_name' => 'HeureTravailController', 'heurtravaildjour'=>$infotimetravaill,'liste'=>$input
        ]);
    }
 

 public function gettimeparjour(DocumentManager $dm){
    $dm->getSchemaManager()->dropDocumentCollection(HeureTravaille::class);
    $her=0;
    $min=0;
    $sec=0;
    $listedateersss=array();
    $input= array();
    $heures=array();
    $outputdate=array();
    $inputdate= array();
    $rslt=array();
    $listedateers=array();
    $combin=array();
    
    $listedateess=array();
    $listedateer=array();
    $listedateerss=array();
    $listedatee =array();
  
    
    $listdate = $dm->getRepository(ChektTimeIn::class)->findAll();

          
    foreach ($listdate as $lst)  {
      if(! in_array($lst->getDate() , $listedatee)){
           array_push($listedatee,$lst->getDate()); }
      
      
          
           
      $listedatee=array_unique($listedatee);}

      $listdate = $dm->getRepository(ChektTimeIn::class)->findAll();
  
       foreach ($listdate as $lst)  {
          
          $listedateess[$lst->getIdEmp()][]=$lst->getDate();}
          foreach($listedateess as $k=>$v){
              $listedateerss=array_unique($v);
              // // echo $k;
              $listedateersss[]= array('id'=>$k,
              'liste'=>$listedateerss
           );
              // $listedateers[]= array('liste'=>$listedateer);
             
        }
      
    


 
        $employes = $dm->getRepository(Employe::class)->findAll();
        
        foreach($employes as $employer){
     
             foreach($listedateersss as $d){
                if($d['id' ]== $employer->getIdemp()){
            
                foreach($d['liste'] as $date ){
          
               
                    
                $k=0 ;
               $i=0;
               $kk=1;
               $ii=1 ;
               $hr='00:00:00';
             
            
       
            $tmpout = $dm->getRepository(ChektTimeOut::class)->findBy(array('idEmp' =>$employer->getIdemp() , 'date' => $date),array('temps'=>'desc'));
            $tmpin = $dm->getRepository(ChektTimeIn::class)->findBy(array('idEmp' =>$employer->getIdemp() , 'date' => $date),array('temps'=>'desc'));

            echo 'id'.$employer->getIdemp();
             echo'jour'.$date;
            foreach($tmpout as $outdate){
                $k++;
                
                 $outputdate[$k]=$outdate->getTemps();
                 
              //echo 'date1**'.$outputdate[$k];
                
            }
            echo 'k**totale'.$k;
            foreach($tmpin as $indate){
                $i++;
               
                $inputdate[$i]=$indate->getTemps();
                 //echo 'date2--'.$inputdate[$i];
              
                
            } 
          echo 'i**totale'.$i;
         while($i !=0 && $k !=0){
             
             
                $date1= strtotime($outputdate[$k]);
                
               echo 'date1**'.$outputdate[$k];
                 $date2 = strtotime($inputdate[$i]);
         echo 'date2--'.$inputdate[$i];
                $x=abs($date1-$date2);
                $h=floor(abs($date1-$date2)/(60*60));
                
               
                $m=floor(($x-$h*60*60)/60);
                $s=floor($x-$h*60*60 - $m*60);
                  $herinstante=$h.':'.$m.':'.$s;
                  $secs=strtotime($herinstante)-strtotime("00:00:00");
                  $herefinal=date("H:i:s",strtotime($hr)+$secs);
                  $hr=$herefinal ;
                  
               
                 // echo 'her'.$hr;
           
            $i-- ;
           
            $ii++;
            echo 'i'.$i.'ii'.$ii;
            $k-- ;
            $kk++;
            echo 'k'.$k.'kk'.$kk;
            
             } 
            // echo 'date'.$employer->getIdemp();
        if($employer->getIdemp()!=''){
        $nbrheure = new HeureTravaille();
        $nbrheure->setIdEmp($employer->getIdemp());
        $nbrheure->setNumrosociete($employer->getSociete());
        $nbrheure->setJour($date);
        $nbrheure->setNbrheure($hr);
        $dm->persist($nbrheure);
       // $dm->flush();
        }
        
            
                        
   
    
         }

              
    }}
    }
   }
     
}
