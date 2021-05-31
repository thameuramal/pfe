<?php

namespace App\Controller\Admin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Societes;
use App\Document\AlerteReatard;
use App\Document\Employe;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Document\Retard;
use App\Document\ChektTimeIn;
class EnvoyeralerteAdController extends AbstractController
{
    /**
     * @Route("/envoyeralerte/ad", name="envoyeralerte_ad")
     */
    public function index(DocumentManager $dm): Response
    {
        $alert=array();
         $infoemp =array();
         $alerts=array();
         $infoemps =array();
         $infoempse =array();
         $listedateerss=array();
        $listedateersss=array();
         $listdate = $dm->getRepository(ChektTimeIn::class)->findAll();
         //////////////// liste date 
           foreach ($listdate as $lst)  {
           // echo 'date'.$lst->getIdEmp();
   $listedateess[$lst->getIdEmp()][]=$lst->getDate();}
   foreach($listedateess as $k=>$v){
       $listedateerss=array_unique($v);
        
       $listedateersss[]= array('id'=>$k,
       'liste'=>$listedateerss
   ); }
         //cas au tous 0 et 1 seul date 
        $count = $dm->createQueryBuilder('App\Document\AlerteReatard')->count()->getQuery()->execute();
        if($count ==0 ){
        $employes = $dm->getRepository(Employe::class)->findAll();
        foreach($employes as $employer){
            foreach($listedateersss as $liste){
                if($liste['id']== $employer->getIdemp()){
                    foreach($liste['liste'] as $date ){
                        //echo $employer->getIdemp();
            $nbalerte= new AlerteReatard();
            $nbalerte->setIdemp($employer->getIdemp());
            $nbalerte->setSociete($employer->getSociete());
            $nbalerte->setNbAlerte('0');
            $nbalerte->setDate($date);
            $dm->persist($nbalerte);
                       $dm->flush();
                    }
                }
            }
            
        }
    }
        else
         {
            // $employes = $dm->getRepository(Employe::class)->findAll();
            // foreach($employes as $employer){
            //     foreach($listedateersss as $liste){
            //         if($liste['id']== $employer->getIdemp()){
            //             foreach($liste['liste'] as $date ){
            //                 $emp = $dm->createQueryBuilder('\App\Document\Employe')
            //                 ->field('idemp')->equals($employer->getIdemp())
            //                 ->field('date')->equals($date)
            //                 ->getQuery() 
            //                 ->getSingleResult();
            //                 if($emp == null ){
            //                 //echo $employer->getIdemp();
            //     $nbalerte= new AlerteReatard();
            //     $nbalerte->setIdemp($employer->getIdemp());
            //     $nbalerte->setSociete($employer->getSociete());
            //     $nbalerte->setNbAlerte('0');
            //     $nbalerte->setDate($date);
            //     $dm->persist($nbalerte);
            //                $dm->flush();}
            //             }
            //         }
            //     }
                
            //}
        
            $societes = $dm->getRepository(Societes::class)->findAll();
            foreach($societes as $societe){
            $employes = $dm->getRepository(Employe::class)->findBy(['societe' =>$societe->getNumero()]);
            foreach($employes as $emp){
                $nbalartes = $dm->getRepository(AlerteReatard::class)->findBy(['idemp' =>$emp->getIdemp()]);
             $alert[]=array('infoalert'=>$nbalartes
            );
            }
            $infoemp [] =  array(
                'nomsociete'=>$societe->getNom(),
                'idsociete'=>$societe->getNumero(),
                'employe'=>$employes,
                'alert'=>$alert
    
            );
            }}
        // $societes = $dm->getRepository(Societes::class)->findAll();
        // foreach($societes as $societe){
        // $employes = $dm->getRepository(Employe::class)->findBy(['societe' =>$societe->getNumero()]);
        // foreach($employes as $emp){
        //     $nbalartes = $dm->getRepository(AlerteReatard::class)->findBy(['idemp' =>$emp->getIdemp()]);
        //  $alert[]=array('infoalert'=>$nbalartes
        // );
        // }
        // $infoemp [] =  array(
        //     'nomsociete'=>$societe->getNom(),
        //     'idsociete'=>$societe->getNumero(),
        //     'employe'=>$employes,
        //     'alert'=>$alert

        // );
        // }
                  

        
        return $this->render('Admin/envoyeralerte_ad/index.html.twig', [
            'controller_name' => 'EnvoyeralerteAdController','infoemp'=>$infoemp
        ]);
    }
    /**
     * @Route("/envoyeenretard", name="envoyeenretard")
     */
    public function listeemployeeretard(DocumentManager $dm ,Request $request): Response
    { dump($request);
        // echo 'tst'.$request->get('temps');
        $societes = $dm->getRepository(Societes::class)->findAll();
        foreach($societes as $societe){
        $employes = $dm->getRepository(Employe::class)->findBy(['societe' =>$societe->getNumero()]);
        foreach($employes as $employer){
        $retard = $dm->getRepository(Retard::class)->findBy(['idEmp' =>$employer->getIdemp(),'jour' =>'04/03/2021']);
        foreach($retard as $temps) 
        {
            
            $heure =  explode(':', $temps->getNbrheure());
                
             $time= $heure[1].':'.$heure[2];
             if($time> $request->get('temps')){
                //  echo 'temps'.$temps->getNbrheure();
                //  echo 'emp'.$temps->getIdEmp();
                $alerts[]=array('temps'=>$temps->getNbrheure(),
                'idemp'=>$temps->getIdEmp());
             }
              
            }
        
               } 
        $infoempse [] =  array(
            'nomsociete'=>$societe->getNom(),
            'idsociete'=>$societe->getNumero(),
            'employe'=>$employes,
            'alert'=>$alerts,
            'heurertard'=>$request->get('temps')
    


        );
        }
    
        return $this->render('Admin/envoyeralerte_ad/listealerte.html.twig', ['infoemp'=>$infoempse,'heureretard'=>$request->get('temps'),
        'tst'=>'false'
        ]);
    }

    /**
     * @Route("/envoyealerteemp/{{idemp}}/{{idsociete}}/{{heureretard}}", name="envoyealerteemp")
     */ 
    public function envoyeralerte(DocumentManager $dm,MailerInterface $mailer ,$idemp,$idsociete,$heureretard,Request $request): Response
    { dump($request);
        
        // echo 'tst'.$request->get('temps'); 
        $employe = $dm->createQueryBuilder('\App\Document\Employe')
       ->field('idemp')->equals($idemp)
        ->getQuery()
        ->getSingleResult();
        // echo 'echoo'.$employe->getNom();
        // echo 'emailempl'.$employe->getEmail();
        $userRh = $dm->createQueryBuilder('\App\Document\Employe')
        ->field('roles')->equals('ROLE_RH')
        ->field('societe')->equals($idsociete)
        ->getQuery()
        ->getSingleResult();
        // echo 'echoo'.$userRh->getNom();
        // echo 'emailrh'.$userRh->getEmail();
        
       //envoyer les emails 
        $email = (new TemplatedEmail())
        ->from('ahlemthameur0@gmail.com')
          ->to('thameuramel27@gmail.com')
          ->addto('amanithameur132@gmail.com')
       ->subject(' avertissement pour retards')
      
       ->htmlTemplate('emails/emailalerte.html.twig')
       ->context([
           
          'heurereard' =>$heureretard,
          'nom'=>$employe->getNom()
          
      ]);


  $mailer->send($email); 
  //$this->addFlash('message','msg envoye');
  //$this->addFlash('info', 'Votre compte cree avec success');
   //$this->redirectToRoute('newcompter');
//    $nbreretrad =  $dm->getRepository(AlerteReatard::class)->findOneBy(array('idemp' =>$idemp));
       
      
//             //modifier nombre de alerte envoye
//      $nombre=$nbreretrad->getNbalerte();
        
//         $nbreretrad ->setNbalerte(intval($nombre)+1); 
//         $nbreretrad->setDate('04/03/2021');
$nbreretrad =  $dm->getRepository(AlerteReatard::class)->findOneBy(array('idemp' =>$idemp, 'date'=>'04/03/2021'));
            
            $nbreretrad ->setNbalerte('1');  
           
            $dm->flush();
       
          return $this->redirectToRoute('listeempretarde',['heureretard'=>$heureretard,'nomempalerteenvoye'=>$employe->getNom()]);

            // return $this->redirectToRoute('login');
    }
  
    /**
     * @Route("/listeempretarde/{{heureretard}}/{{nomempalerteenvoye}}", name="listeempretarde")
     */
    public function listeempenretarde(DocumentManager $dm ,$heureretard,$nomempalerteenvoye,Request $request): Response
    { dump($request);
        // echo 'tst'.$request->get('temps');
        $societes = $dm->getRepository(Societes::class)->findAll();
        foreach($societes as $societe){
        $employes = $dm->getRepository(Employe::class)->findBy(['societe' =>$societe->getNumero()]);
        foreach($employes as $employer){
        $retard = $dm->getRepository(Retard::class)->findBy(['idEmp' =>$employer->getIdemp(),'jour' =>'04/03/2021']);
        foreach($retard as $temps) 
        {
            
            $heure =  explode(':', $temps->getNbrheure());
                
             $time= $heure[1].':'.$heure[2];
             if($time> $heureretard){
                //  echo 'temps'.$temps->getNbrheure();
                //  echo 'emp'.$temps->getIdEmp();
                $alerts[]=array('temps'=>$temps->getNbrheure(),
                'idemp'=>$temps->getIdEmp());
             }
              
            }
        
               } 
        $infoempse [] =  array(
            'nomsociete'=>$societe->getNom(),
            'idsociete'=>$societe->getNumero(),
            'employe'=>$employes,
            'alert'=>$alerts,
            'heurertard'=>$heureretard
            
    


        );
        }
    
        return $this->render('Admin/envoyeralerte_ad/listealerte.html.twig', ['infoemp'=>$infoempse,'heureretard'=>$heureretard,
        'tst'=>'true','nomempalerteenvoye'=>$nomempalerteenvoye]);
    }
}
