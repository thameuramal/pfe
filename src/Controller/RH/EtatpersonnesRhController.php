<?php

namespace App\Controller\RH;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Societes; 
use App\Document\EtatEmploye; 
use Symfony\Component\HttpFoundation\Request;
use App\Document\Employe;
use App\Document\ChektTimeIn;
class EtatpersonnesRhController extends AbstractController
{
    /**
     * @Route("/etatpersonnel/rh/{{idemp}}/{{nom}}/{{societe}}/{{matricule}}/{{nomsociete}}", name="etatpersonnel_rh")
     */
    public function index(DocumentManager $dm,Request $request,$idemp,$nom,$societe,$matricule,$nomsociete): Response 
    {
       
        return $this->render('RH/etatpersonnes_rh/index.html.twig', [
            'controller_name' => 'EtatpersonnelRhController','idemp'=>$idemp ,'nom'=>$nom,'matricule'=>$matricule,'idsociete'=>$societe,'societe'=>$nomsociete
        ]);
    }
    /**
     * @Route("/listemp", name="listemp")
     */
    public function listeemploye(DocumentManager $dm): Response
    { 
        $user = $this->getUser();
        //echo $user->getNom();
        $societes = $dm->getRepository(Societes::class)->findBy(['numero' =>$user->getSociete()]);
        foreach($societes as $societe){
        $employes = $dm->getRepository(Employe::class)->findBy(['societe' =>$societe->getNumero()]);
        $infoemp [] =  array(
            'nomsociete'=>$societe->getNom(),
            'idsociete'=>$societe->getNumero(),
            'employe'=>$employes 

        ); 
        }
        
        return $this->render('RH/etatpersonnes_rh/listeemp.html.twig', [
            'controller_name' => 'EtatpersonnelRhController','infoemp'=>$infoemp
        ]);
    } 

       /**
     * @Route("/etatmodifier/rh/{{idemp}}/{{idsociete}}/{{matricule}}/{{nomsociete}}/{{nom}}", name="etatmodifier")
     */
    public function modifieretat(DocumentManager $dm,Request $request,$idemp,$idsociete,$matricule,$nomsociete,$nom): Response 
    {
       // dd($request);
        $day = 86400;
        if( $request->query->count()>0){
            //echo 'date'.$request->query->get('to');
            $etat = $request->query->get('role');
           // echo 'etat'.$etat;
            $start_date  = $request->query->get('to');
        $end_date    = $request->query->get('from'); 
        // echo 'datedebut'.$start_date;
      // echo 'datedend'.$end_date;
        //$format = 'Y-m-d'; // Output format (see PHP date funciton)  
        $format = 'd/m/Y';
        $sTime = strtotime($start_date); // Start as time  
        $eTime = strtotime($end_date); // End as time  
        $numDays = round(($eTime - $sTime) / $day) + 1;  
        $days = array();  
        for ($d = 0; $d < $numDays; $d++) {  
           /// $etats = new EtatEmploye(); 
            /// $etats-> setIdEmp($idemp); 
            /// $etats-> setNumrosociete($idsociete);
            /// $etats-> setJour(date($format, ($sTime + ($d * $day))));
            /// $etats->setEtat($request->query->get('role'));
            $nbralete = $dm->createQueryBuilder('\App\Document\ChektTimeIn')
                            ->field('idEmp')->equals($idemp)
                            ->field('date')->equals(date($format, ($sTime + ($d * $day))))
                            ->getQuery()
                            ->getSingleResult();
                            $employedate =  $dm->getRepository(ChektTimeIn::class)->findBy(['idEmp' =>$idemp,'date'=>date($format, ($sTime + ($d * $day)))]);
                            if($employedate!= null){
                                foreach($employedate  as $emp){
                                  //  echo'existe'; 
                                $emp->setEtat($request->query->get('role'));
                                 $dm->flush();
                                }
                                
                                
                                 
                            }else{
                               // echo 'notexiste';
            $etat = new ChektTimeIn(); 
            $etat-> setIdEmp($idemp); 
            $etat-> setNumeroSocietes ($idsociete);
            $etat-> setDate(date($format, ($sTime + ($d * $day))));
            $etat->setEtat($request->query->get('role'));

            //echo '*******date******'.date($format, ($sTime + ($d * $day))).'//';  
            ///$dm->persist($etats);
        ///$dm->flush();
            $dm->persist($etat);
        $dm->flush();
        }}
        }
        return $this->render('RH/etatpersonnes_rh/index.html.twig', [
            'controller_name' => 'EtatpersonnelRhController','idemp'=>$idemp ,'nom'=>$nom,'matricule'=>$matricule,'idsociete'=>$idsociete,'societe'=>$nomsociete
        ]);
       // return $this->redirectToRoute('listemp');
    }
    /**
     * @Route("/tstetat", name="tstetat")
     */
    public function tst(DocumentManager $dm,Request $request): Response 
    {
        dump($request);
         if( $request->query->count()>0){
        //     echo 'date debut'.$request->query->get('to');
        //     echo 'date fin'.$request->query->get('from');
        //     $datedebut =  date('d/m/Y', strtotime($request->query->get('to')));
        //     $datefin =  date('d/m/Y', strtotime($request->query->get('from')));
        //     echo 'datefin'.$datefin.'datedebut'.$datedebut;
        
        // }
        $day = 86400; // Day in seconds 
       // $start_date  = '2020/09/23';
        //$end_date    = '2020-10-03'; 
         $start_date  = $request->query->get('to');
        $end_date    = $request->query->get('from'); 
         echo 'datedebut'.$start_date;
         echo 'datedend'.$end_date;
        //$format = 'Y-m-d'; // Output format (see PHP date funciton)  
        $format = 'd/m/Y';
        $sTime = strtotime($start_date); // Start as time  
        $eTime = strtotime($end_date); // End as time  
        $numDays = round(($eTime - $sTime) / $day) + 1;  
        $days = array();  
        for ($d = 0; $d < $numDays; $d++) {  
            echo '*******date******'.date($format, ($sTime + ($d * $day))).'//';  
        }
         }
         
        return $this->render('login/testtwig.html.twig');
    }
}
