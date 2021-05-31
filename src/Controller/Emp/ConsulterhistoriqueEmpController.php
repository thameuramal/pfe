<?php

namespace App\Controller\Emp;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Societes;
use App\Document\EtatEmploye;
use App\Document\Employe;

use App\Document\ChektTimeIn;
use App\Document\ChektTimeOut;

class ConsulterhistoriqueEmpController extends AbstractController
{
    /**
     * @Route("/consulterhistorique/emp", name="consulterhistorique_emp")
     */
    public function index(DocumentManager $dm): Response
    {
        $user = $this->getUser();
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
           
            $listdate = $dm->getRepository(ChektTimeIn::class)->findBy(array(['idEmp'=>$user->getIdemp()]),array('date'=>'ASC'));
            
             foreach ($listdate as $lst)  {
                 
                $listedateess[$lst->getIdEmp()][]=$lst->getDate();}
                foreach($listedateess as $k=>$v){
                    $listedateerss=array_unique($v);
                 
                    $listedateersss[]= array('id'=>$k,
                    'liste'=>$listedateerss
                 );
                    
                   
              }
            $socts =$dm->getRepository(Societes::class)->findBy(['numero'=>$user->getsociete()]);
            foreach($socts as $soc){
              $employess = $dm->getRepository(Employe::class)->findBy(['societe' =>$soc->getNumero()]);
              foreach($employess as $empp){
                foreach($listedateersss as $d){
                  if($d['id' ]== $empp->getIdemp()){
                    foreach($d['liste'] as $date ){
                      $k=0;
                      $i=0;
                
                      $outtimes =$dm->getRepository(ChektTimeOut::class)->findBy(['idEmp' =>$empp->getidEmp(),'date'=>$date],array('temps'=>'DESC'));

             
              foreach( $outtimes as $t){
                $k++;
                
                 $outputdate[$k]=$t->getTemps();}
            $intimes =$dm->getRepository(ChektTimeIn::class)->findBy(['idEmp' =>$empp->getidemp(), 'date' => $date],array('temps'=>'ASC'));

            foreach( $intimes as $t){
            
              if($k==0){
               
                
                $listtemps[]=array(
                  'date'=>$t->getDate(), 
                 'in'=>$t->getTemps(),
                 'etat'=>$t->getEtat(),
                  'out'=>'-',
                  'id'=>$t->getIdEmp()
                );
                
                
               }
            while($k!=0){ 
               
 if($outputdate[$k] <$t->getTemps()){
 
  

   $listtemps[] = array(
     'date'=> $t->getDate(), 
     'in'=> $t->getTemps(),
     'etat'=>$t->getEtat(),
     'out'=> $outputdate[$k],
     'id'=>$t->getIdEmp()
     );
     $k--;
   break;
 }
 else{
  
  
  $listtemps[]=array(
    'date'=>$t->getDate(), 
   'in'=>$t->getTemps(),
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

        $etatemp =$dm->getRepository( EtatEmploye::class) ->findAll();
        foreach($etatemp as $etats){
          $employesetat = $dm->getRepository(Employe::class)->findBy(['idemp' =>$etats->getIdEmp()]);
    
          $societeetat=$dm->createQueryBuilder('\App\Document\Societes')
          ->field('numero')->equals($etats->getNumrosociete())
           ->getQuery()
          ->getSingleResult();
          
         
         
      
        $infetat[] =  array('numerosociete'=>$etats->getNumrosociete(),'idemp'=>$etats->getIdEmp() ,'dateetat'=>$etats->getJour(), 'etatemploye'=>$etats->getEtat() , 'etatempl'=>$employesetat,'soieteetat'=>$societeetat->getNom());
       
      }
        
         
        return $this->render('Emp/consulterhistorique_emp/index.html.twig', [
            'controller_name' => 'ConsulterhistoriqueEmpController','listetemps'=>$infoins, 'etat'=>$infetat
        ]);
    }
}
