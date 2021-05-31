<?php

namespace App\Controller\Admin; 

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Societes;
 
use App\Document\Employe;

use App\Document\ChektTimeIn;
use App\Document\ChektTimeOut;
use Symfony\Component\Routing\Annotation\Route;
use App\Document\EtatEmploye;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class HistoriqueadController extends AbstractController
{
  // @Route("/historiquead", name="historiquead")
    /**
     * * @IsGranted("ROLE_ADMIN")
     * @Route("/historiquead", name="historiquead")
     */
    public function index(DocumentManager $dm ): Response
    {
      
      
      // $results  = new ChektTimeIn();
      // $results->setIdEmp('88');
      // $results->setNumeroSocietes('2');
      // $results->setTemps('08:00:00');
      // $results->setDate('03/05/2021'); 
      // $dm->persist($results);
      //  $dm->flush();
      // if($results== null){echo 'not existe';}
      // else {echo 'existe';
      //   foreach()
      // }
      $input= array();
      $outemps= array();
      $datearray= array();
      $listedatee =array();
      $listedateers=array();
      $listedateerstst=array();
      $listedateer =array();
      $listedateeout =array();
      $outtimein=array();
      //$timein = new ChektTimeIn();
        // echo  "nom".$empinfo->getId(); 
        // $timein ->setIdEmp('88');
        // $timein ->setNumeroSocietes('2');
        // //$dates = strtotime('17/05/2021 12:08:58');
        // // $datesin= date('d/m/Y', $dates);
        // $timein ->setDate('19/05/2021');
        
        //  //$tempsin= date('H:i:s', $dates);
        // $timein ->setTemps('12:08:58');
        // //$timein ->setEtat('en production');
     
        //  $dm->persist($timein);
        // $dm->flush();
      
      // $cnx =$this->connect();
        
    
      //  $results = $dm->getRepository(ChektTimeIn::class)->findBy(array(),array('id'=>'DESC'),1,0);
      //  $date=$results[0]->getDate();
       //echo $date;
      
      $societe =  $dm->getRepository(Societes::class)->findAll();

      $employe = $dm->getRepository(Employe::class)->findAll();
            
        $infotime = $dm->getRepository(ChektTimeIn::class)->findAll();
        foreach($societe as $societes){
          $numsociete= $societes->getNumero();
         
          $employes = $dm->getRepository(Employe::class)->findBy(['societe' =>$numsociete]);
          
          foreach($employes as $employer){
            
            $tmpout = $dm->getRepository(ChektTimeOut::class)->findBy(['idEmp' =>$employer->getIdemp()],array('temps' => 'asc'));
            $tmp = $dm->getRepository(ChektTimeIn::class)->findBy(['idEmp' =>$employer->getIdemp()] ,array('temps' => 'asc'));
      //       foreach ($tmp as $lsttmp)  {
      //       $listdate = $dm->getRepository(ChektTimeIn::class)->findBy(['idEmp' =>$employer->getIdemp(),"date"=>$lsttmp->getDate()]);
      //       foreach ($listdate as $lst)  {
      //         // if(! in_array($lst->getDate() , $listedateer[])){

      //             //  array_push($listedateer,$lst->getDate()); }
              
      //         $listdateer[]=array(
      //           'idemp'=>$employer->getIdemp(),
      //           'liste'=>$lst->getDate());}
                  
                   
      //         $listedatee=array_unique($listedateer);
      //       // }

      //       foreach($listdateer as $ll){
      //     echo 'date'.$ll['liste'];
      //   }
      // }

            // foreach($tmpout as $tmps){
              
              
                
            //      $datetst = $dm->getRepository(ChektTimeOut::class)->findBy(['date' =>$tmps->getDate()]);
               
            //    }

            $outtimein[]= array (
              // 'tstliste'=>$listdateer,
              'id'=>$employer->getIdemp(),
              'timein'=>$tmp,
              'timeout'=>$tmpout,
              // 'timee'=>$datetst
              
                );
          }

          // foreach($employes as $employer){
          //    $tmp = $dm->getRepository(ChektTimeIn::class)->findBy(['idEmp' =>$employer->getIdemp()]);
            
            
          //    $iid=$employer->getIdemp();
          //   // foreach($tmp as $tmps){
              
          //   // //    if(! in_array($tmps->getDate() , $datearray)){
          //   // //     array_push($datearray,$tmps->getDate()); }
             
          //   // //   // echo 'date'.$tmps->getDate();
              
          //   //    $datetst = $dm->getRepository(ChektTimeIn::class)->findBy(['date' =>$tmps->getDate()]);
          //   //   //  foreach( $datetst as $temp){
                
          //   //   //   // array_push($datearray,$temp); //  }
          //   //  }
          //    $outtime[]= array (
          //     'id'=>$employer->getIdemp(),
          //     'time'=>$tmp,
          //     'timeout'=>$tmpout,
          //     'date'=>$datearray,
          //          'timee'=>$datetst
          //       );
          //  $outemps [] =   array
          //   ('temps'=>$tmp);
           
          // }
          //  $input[$societes->getNom()]=$employes;
          //partie twig
          // {% for input in outpot%}
          // {{ loop.index }} 
          // {% for emp in input %}
          // {{emp.nom}}
           $input [] =  array
             ('nome' => $societes->getNom(), 
            'employe' => $employes,
            // 'date'=>$datearray
            //  'tmps'=>$outemps
            // 'idd'=>$iid,
            // 'tmps'=>$outtime,
            'tmpsout'=>$outtimein
            
        );
             
       
        }
        
 
        // $listdate = $dm->getRepository(ChektTimeIn::class)->findBy(array(),array('date' => 'asc'));
        // $listdateetat = $dm->getRepository(EtatEmploye::class)->findAll();
        // foreach ($listdateetat as $lst)  {
        //   $listedatee[$lst->getIdEmp()][]=$lst->getJour();
        // }
        
         $listdate = $dm->getRepository(ChektTimeIn::class)->findBy(array(),array('id'=>'DESC'));
        foreach ($listdate as $lst)  {
          // if(empty($listedatee)){
          //   // echo 'empty';
          //   $listedatee[$lst->getIdEmp()][]=$lst->getDate();
          // } else{
          //   foreach($listedatee as $k=>$v){

          //     if($k == $lst->getIdEmp()){
          //     foreach($v as $vv){
          //       { if($vv != $lst->getDate()){
          //         $listedatee[$k][]=$vv;

          //     }
             
          //   }}
          //     }
           
          // }}
          // foreach($listedatee as $k=>$v ){
            // if( $v == $lst->getDate() && $k == $lst->getIdEmp() ){break ;}
          // if(! is_in_array($lst->getDate() , $listedatee)){
            // $listdatee[$lst->getIdEmp()]=$lst->getDate();
            // else{
             // $listedatee[$lst->getIdEmp()][]=$lst->getDate();
           ///********* */   $count = $dm->createQueryBuilder('App\Document\EtatEmploye')->count()->getQuery()->execute();
             // echo 'count'.$count;
             ///******* */ if($count ==0){
              // echo $lst->getDate().' ||'.$lst->getEtat();
                $listedatee[$lst->getIdEmp()][]=$lst->getDate().' '.$lst->getEtat(); 

             ///****** */ }else{
              //****$listdateetat = $dm->getRepository(EtatEmploye::class)->findBy(array(),array('id'=>'DESC'));
       ///*** */ foreach ($listdateetat as $lste)  {
           //------------
        //    $jour =  explode('/', $lste->getJour());****
        // $tt = $jour[2].'-'.$jour[1].'-'.$jour[0];*****
        // $joure =  explode('/', $lst->getDate());****
        // $tte = $joure[2].'-'.$joure[1].'-'.$joure[0];***
//echo 'tstdate'.$tte;
       
//*******if(($tte > $tt)){
           //---------------
          //if(strtotime($lste->getJour())< strtotime($lst->getDate())){
         ///*************** */   $listedatee[$lst->getIdEmp()][]=$lst->getDate().' '.'production';
            // $listedatee[$lste->getIdEmp()][]=$lste->getJour().' '.$lste->getEtat();
            //echo'dateentre'.$tte.'//'.$tt.'production';
        //   ***}else{
        //   **$listedatee[$lste->getIdEmp()][]=$lste->getJour().' '.$lste->getEtat();
        //   **$listedatee[$lst->getIdEmp()][]=$lst->getDate().' '.'production';
        // 
        // **}}
     //**** */   } 
              //$listedatee[$lst->getIdEmp()][]=$lst->getDate();

            // $listedatee[]= array($lst->getIdEmp()=>$lst->getDate());
          
            // $l=array_push_assoc($listedatee,$lst->getIdEmp(),$lst->getDate()); }
              //  array_push($listedatee['id'],$lst->getDate()); 
              
              // }
          
          // $listdatee[]=array('liste'=>$lst->getDate());
          
        // foreach($listedatee as $k=>$v){
        //   $listedateer=array_unique($v);
        //   echo $k;
        //  }
        //  $listedateers[]= array('id'=>$lst->getIdEmp(),
        //  'liste'=>$listedateer);
         
       
        
            

           // $listedatee=array_unique($listedatee);
       }

      //  foreach($listedatee as $k=>$v){
      //   $listedateertst=array_unique($v);
      //   foreach($listedateerstst as $tte=>$tt){
          
      //     echo 'dd'.$tt;
      //    foreach
      //   }
      //   $listedateerstst[]= array('liste'=>$listedateerstst);
      //   //foreach($v as $m){echo 'date'.$m;}
      // }
       
            
         
      
 

        foreach($listedatee as $k=>$v){
          $listedateer=array_unique($v);
          //sort($listedateer);
          // echo $k;
          $listedateers[]= array('id'=>$k,
             'liste'=>$listedateer
          );
          
        }
        
       
        // $listedateer[]=array_keys($listedatee);
        

        // $listdateout = $dm->getRepository(ChektTimeOut::class)->findAll();
        // foreach ($listdateout as $lst)  {

   

        //   if( in_array($lst->getDate() , $listedateeout)){
        //        array_push($listedateeout,$lst->getDate()); }
          
        //   // $listdatee[]=array('liste'=>$lst->getDate());
              
               
        //   $listdateeout=array_unique($listedateeout);
        // }

        // foreach($listedatee as $ll){
        //   echo 'date'.$ll;
        // }
        
        $infotimeout = $dm->getRepository(ChektTimeOut::class)->findAll();

        $etat = $dm->getRepository(EtatEmploye::class)->findAll();

 

        
        
        return $this->render('Admin/historiquead/index.html.twig', [
      
            'controller_name' => 'HistoriqueadController','societe'=>$societe , 'etat'=>$etat ,'time'=>$infotime, 'employe'=>$employe, 'timeout'=>$infotimeout, 'outpot'=>$input , 'listedate'=>$listedatee ,'listers'=>$listedateers, 'tstliste'=>$listedateerstst
        ]); 
    }

    public function connect()
    {
      $dataBaseName = $this->getParameter('namedbacces');
      $conn=odbc_connect($dataBaseName,"","");
        return $conn;
     }

   
}
