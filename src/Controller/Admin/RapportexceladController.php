<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Societes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Document\Employe;
use App\Document\Retard;
use App\Document\ChektTimeIn;
use App\Document\ChektTimeOut;
use App\Document\AlerteReatard;
class RapportexceladController extends AbstractController
{
    /**
     * * @IsGranted("ROLE_ADMIN")
     * @Route("/rapportexcelad", name="rapportexcelad")
     */
    public function index(DocumentManager $dm): Response
    {    
        

    
        //echo 'alerte'.$alert->getNbAlerte();
                 
        $nbraletes = $dm->getRepository(AlerteReatard::class)->findAll();
        
        $listedateerss=array();
        $listedateersss=array();
        $heures=array();
        $listdate = $dm->getRepository(ChektTimeIn::class)->findAll();
              //////////////// liste date 
                foreach ($listdate as $lst)  {
                // echo 'date'.$lst->getIdEmp();
        $listedateess[$lst->getIdEmp()][]=$lst->getDate();}
        foreach($listedateess as $k=>$v){
            $listedateerss=array_unique($v);
             //$y=array_revers($listedateerss);
            $listedateersss[]= array('id'=>$k,
            'liste'=>$listedateerss
        ); }
        
        //recupere donnees aprtir base 
        $societe =  $dm->getRepository(Societes::class)->findAll();
        foreach($societe as $societes){
            
            $employes = $dm->getRepository(Employe::class)->findBy(['societe' =>$societes->getNumero()]);
            foreach($employes as $employer){
                
                foreach($listedateersss as $d){
                    
                    if($d['id' ]== $employer->getIdemp()){


                        foreach($d['liste'] as $jour ){
                           // echo 'date'.$jour;
                            $nbraleter = $dm->getRepository(AlerteReatard::class)->findBy(['idemp' =>$employer->getIdemp(),'date'=>$jour]);

                            $nbralete = $dm->createQueryBuilder('\App\Document\AlerteReatard')
                            ->field('idemp')->equals($employer->getIdemp())
                            ->field('date')->equals($jour)
                            ->getQuery()
                            ->getSingleResult();
        //                     echo 'id'.$nbralete->getIdemp();
        // echo 'date'.$nbralete->getDate();
           //echo 'nbalert'.$nbralete->getNbalerte()
        // foreach($nbraleter as $nbraletes ){
        //  echo 'nbalert'.$nbraletes->getNbalerte();}
                           
                        $heuereretard = $dm->createQueryBuilder('\App\Document\Retard')
        ->field('idEmp')->equals($employer->getIdemp())
        ->field('jour')->equals($jour)
        ->getQuery()
        ->getSingleResult();
        //if($heuereretard != null){echo 'existe';}else { echo'notexiste';}
        
         //echo 'id'."tst".$heuereretard->getIdEmp();
        // echo 'date'.$heuereretard->getJour();
        // echo 'heureretard'.$heuereretard->getNbrheure();
        $heuertravail = $dm->createQueryBuilder('\App\Document\HeureTravaille')
        ->field('idEmp')->equals($employer->getIdemp())
        ->field('jour')->equals($jour)
        ->getQuery()
        ->getSingleResult();
        
        // echo 'heuretravail'.$heuertravail->getNbrheure();
        
        $heures[]=array('id'=>$employer->getIdemp(),
            'date'=>$jour, 'heureretard'=>$heuereretard->getNbrheure() , 'alertejr'=>$nbraleter,
                    'heuretravail'=>$heuertravail->getNbrheure());
                 } }}}
                 $infoemp [] =  array
           ('nomsociete' => $societes->getNom(), 
          'employe' => $employes,
          'idsociete'=>$societes->getNumero(),
          
          'heur'=>$heures
      
          
      );  
            }
        return $this->render('Admin/rapportexcelad/index.html.twig', [
            'controller_name' => 'RapportexceladController','infoemp'=>$infoemp ,'listedate'=>$listedateersss
        ]);
    }

    /**
     * @Route("/rapportst", name="tst")
     */
    public function tst(DocumentManager $dm): Response
    {   
        return $this->render('Admin/rapportexcelad/tst.html.twig', [
           
        ]);
    }
}
