<?php

namespace App\Controller\Admin; 

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Societes;
use App\Document\EtatEmploye;
use App\Document\Employe;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Document\ChektTimeIn;
use App\Document\ChektTimeOut;

class HistoriquepardateadController extends AbstractController
{
    /**
     * * @IsGranted("ROLE_ADMIN")
     * @Route("/historiquepardatead", name="historiquepardatead")
     */
    public function index(DocumentManager $dm): Response

    {
        $infoout=array();
            $infotimein=array();
            $listesemplin=array();
            $newliste=array();
            $listeinout=array();
            $listtemps=array();
            $outputdate=array();
          
            //$infetat=array();
             $listeetat=array();
            $listedateess=array();
            $listedateer=array();
            $listedateerss=array();
            $listedatee =array();
            ////////////////test liste
            $listdate = $dm->getRepository(ChektTimeIn::class)->findBy(array(),array('date'=>'ASC'));
            
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
            $socts =$dm->getRepository(Societes::class)->findAll();
            foreach($socts as $soc){
              $employess = $dm->getRepository(Employe::class)->findBy(['societe' =>$soc->getNumero()]);
              foreach($employess as $empp){
                foreach($listedateersss as $d){
                  if($d['id' ]== $empp->getIdemp()){
                    foreach($d['liste'] as $date ){
                      $k=0;
                      $i=0; 
                
                      $outtimes =$dm->getRepository(ChektTimeOut::class)->findBy(['idEmp' =>$empp->getidEmp(),'date'=>$date],array('temps'=>'DESC'));

              //new 
              foreach( $outtimes as $t){
                $k++;
                
                 $outputdate[$k]=$t->getTemps();}
            $intimes =$dm->getRepository(ChektTimeIn::class)->findBy(['idEmp' =>$empp->getidemp(), 'date' => $date],array('temps'=>'ASC'));

            foreach( $intimes as $t){
              // $outtimes =$dm->getRepository(ChektTimeOut::class)->findBy(['idEmp' =>$t->getidEmp(),'date'=>$t->getDate()]);
              $intimevalue=$t->getTemps();
              if($intimevalue==null){
                $intimevalue='-';
              }
              // //new 
              // foreach( $outtimes as $t){
              //   $k++;
              //   // echo 'ii'.$k;
              //   //  $outputdate= array($outdate->getTemps());
              //    $outputdate[$k]=$t->getTemps();}
              //new
              if($k==0){
                // echo'dates'. $t->getDate(); 
                // echo 'ins'. $t->getTemps();
                // echo'outs'.'-';
                
                $listtemps[]=array(
                  'date'=>$t->getDate(), 
                 'in'=>$intimevalue,
                 'etat'=>$t->getEtat(),
                  'out'=>'-',
                  'id'=>$t->getIdEmp()
                );
                
                
               }
            while($k!=0){ 
                //  echo 'idemp'.$t->getIdEmp();
 if($outputdate[$k] <$intimevalue){
 
  // echo'date'. $t->getDate();
  // echo 'in'. $t->getTemps();
  // echo'out'.$outputdate[$k];

   $listtemps[] = array(
     'date'=> $t->getDate(), 
     'in'=> $intimevalue,
     'etat'=>$t->getEtat(),
     'out'=> $outputdate[$k],
     'id'=>$t->getIdEmp()
     );
     $k--;
   break;
 } 
 else{
  // echo'dates'. $t->getDate(); 
  // echo 'ins'. $t->getTemps();
  // echo'outs'.$outputdate[$k];
  
  $listtemps[]=array(
    'date'=>$t->getDate(), 
   'in'=>$intimevalue,
   'etat'=>$t->getEtat(),
    'out'=>$outputdate[$k],
    'id'=>$t->getIdEmp()
  );
  $k--;
  break;
  
 }
 
 
}}
}}
            }
            $tt[]=array(
              'liste'=>$listtemps,
              'id'=>$empp->getidemp()
            );

              }

              $infoins [] =  array
              (
                'nomsocit'=>$soc->getNom(),
             'id'=>$soc->getNumero(),
             
             'emp'=>$employess,
             'listes'=>$tt);
            }
//version1
  //           $socts =$dm->getRepository(Societes::class)->findAll();
  //           foreach($socts as $soc){
  //             $employess = $dm->getRepository(Employe::class)->findBy(['societe' =>$soc->getNumero()]);
  //             foreach($employess as $empp){
  //               $outtimes =$dm->getRepository(ChektTimeOut::class)->findBy(['idEmp' =>$empp->getidemp()]);
  //               $intimes =$dm->getRepository(ChektTimeIn::class)->findBy(['idEmp' =>$empp->getidemp()]);
               
  //              $listeinout[]=array(
  //                'idemp'=>$empp->getidemp(),
  //                'in'=>$intimes,
  //                'out'=>$outtimes
  //              ); 
  //             }
  //          $newliste[]=array(
  //            'nomsocit'=>$soc->getNom(),
  //            'id'=>$soc->getNumero(),
  //            'emp'=>$employess,
  //            'liste'=>$listeinout
  //          );
  //           }

  //           // ancien ---------------------
  //       $outtime =$dm->getRepository(ChektTimeOut::class)->findAll();

  //       foreach($outtime as $time){
            
  //         $employes = $dm->getRepository(Employe::class)->findBy(['idemp' =>$time->getidEmp()]);
  //         $societes = $dm->getRepository(Societes::class)->findBy(['numero' =>$time->getNumeroSocietes()]);


  //       $infoout [] =  array
  //       (
  //      'idemp' => $time->getIdEmp(),
  //      'date'=>$time->getDate(),
  //      'temps'=>$time->getTemps(),
  //      'listeemp'=>$employes,
  //   'listesocietes'=> $societes
       

       
  //  );
  //       }
  


  //       $intime =$dm->getRepository(ChektTimeIn::class)->findAll();

  //       foreach($intime as $time){
            
  //         $employes = $dm->getRepository(Employe::class)->findBy(['idemp' =>$time->getidEmp()]);
  //         $societes = $dm->getRepository(Societes::class)->findBy(['numero' =>$time->getNumeroSocietes()]);


  //       $infoin [] =  array
  //       (
  //      'idemp' => $time->getIdEmp(),
  //      'date'=>$time->getDate(),
  //      'temps'=>$time->getTemps(),
  //      'listeemp'=>$employes,
  //   'listesocietes'=> $societes
       
  //   //    'timein'=>$infotimein
       
  //  );
        // }
  //       $etatemp =$dm->getRepository( EtatEmploye::class) ->findAll();
  //       foreach($etatemp as $etats){
  //         $employesetat = $dm->getRepository(Employe::class)->findBy(['idemp' =>$etats->getIdEmp()]);
  //        // $societeetat= $dm->getRepository(Societes::class)->findBy(['numero'=>$etats->getNumrosociete()]);
  //         $societeetat=$dm->createQueryBuilder('\App\Document\Societes')
  //         ->field('numero')->equals($etats->getNumrosociete())
  //          ->getQuery()
  //         ->getSingleResult();
          
  //         //echo'etat'.$societeetat->getNumero();
  //        // $listeetat[]=array('etatempl'=>$employesetat,'soieteetat'=>$societeetat->getNumero());
  //         //          $listeetat[]=array('ideata'=>$e->getIdemp(), 'dateetat'=>$e->getJour(),'etat'=>$e->getEtat(),'societeetat'=>$e->getNumrosociete());

        
         
      
  //       $infetat[] =  array('numerosociete'=>$etats->getNumrosociete(),'idemp'=>$etats->getIdEmp() ,'dateetat'=>$etats->getJour(), 'etatemploye'=>$etats->getEtat() , 'etatempl'=>$employesetat,'soieteetat'=>$societeetat->getNom());
  // //     
  //     }
     
  
        return $this->render('Admin/historiquepardatead/index.html.twig', [
            'controller_name' => 'HistoriquepardateadController','listetemps'=>$infoins
        ]);
    }
}
