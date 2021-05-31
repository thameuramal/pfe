<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport ;
use Symfony\Component\Mailer\Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Societes;
use App\Document\Employe;
use App\Document\Retard;
use App\Document\ChektTimeIn;
use App\Document\ChektTimeOut;
 

use App\Document\ParametreGenerale; 
use App\Document\ParametrageEmail;

class EnvoyeAlerteCommand extends Command
{
    private $documentManager;
    protected static $defaultName = 'EnvoyeAlerte';
    protected static $defaultDescription = 'Add a short description for your command';

    public function __construct( DocumentManager $documentManager) // <- Add this
    {
        parent::__construct();
        $this->documentManager = $documentManager;
        
    }
    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
 
      $listedateersss=array();
      $listdate = $this->documentManager->getRepository(ChektTimeIn::class)->findAll();
      ////////////////test liste
       foreach ($listdate as $lst)  {
$listedateess[$lst->getIdEmp()][]=$lst->getDate();}
foreach($listedateess as $k=>$v){
    $listedateerss=array_unique($v);
    // // echo $k;
    $listedateersss[]= array('id'=>$k,
    'liste'=>$listedateerss
); }
$count =  $this->documentManager->createQueryBuilder('App\Document\HeureTravaille')->count()->getQuery()->execute();
if($count!=0){ 
 // $this->documentManager->getSchemaManager()->dropDocumentCollection(Retard::class);
      $this->gettimeparjour($listedateersss);}
      else{
          $this->gettimeparjour($listedateersss); 
      } 
   
        // $employes = $this->documentManager->getRepository(Employe::class)->findAll();
        // foreach($employes as $employer){
            
          
        //             $hr='00:00:00';
        //             $listdate = $this->documentManager->getRepository(ChektTimeIn::class)->findby(array('idEmp' =>$employer->getIdemp(),'date' =>'04/03/2021'),array('temps'=>'DESC'),1,0);
        //             foreach ($listdate as $lst)  {
        //         //    echo 'id'.$lst->getIdEmp();
        //           echo 'nom'.$employer->getNom();
        //          echo 'temps'.$lst->getTemps();
        //              $date1= strtotime($lst->getTemps());
                        
        //              $date2 = strtotime('08:00:00');
        //              if($date1>$date2){
        //              $x=abs($date1-$date2);
        //              $h=floor(abs($date1-$date2)/(60*60));
                     
                    
        //              $m=floor(($x-$h*60*60)/60);
        //              $s=floor($x-$h*60*60 - $m*60);
        //                $herinstante=$h.':'.$m.':'.$s;
        //                $secs=strtotime($herinstante)-strtotime("00:00:00");
        //                $herefinal=date("H:i:s",strtotime($hr)+$secs);
        //                $hr=$herefinal ;}
        //                else{$hr='00:00:00';}
   // }}
        //--------------- 
        $paramtre =  $this->documentManager->getRepository(ParametrageEmail::class)->findAll();
        foreach($paramtre as $parm){
            $host=$parm->getHost();
                
            $mail=$parm->getEmail();
            $password=$parm->getPassword();
            $port=$parm->getPort();
            //$url=$parm->getUrl();
            
        }
        
        $paramtre =  $this->documentManager->getRepository(ParametreGenerale::class)->findAll();
        foreach($paramtre as $paramtres){
          
$url=$paramtres->getUrl();
$senstravail=$paramtres->getSeancetravail(); 
$heurematin=$paramtres->getHeurematin();
$retardmatin=$paramtres->getRetardmatin();
$heureapresmidi=$paramtres->getHeureapresmidi();
$retardapresmidi=$paramtres->getRetardapresmidi();}
       $mailmodifier=str_replace("@","%40",$mail);
         $var='smtp://'.$mailmodifier.':'.$password.'@'.$host.':'.$port;
            //echo $var;
        date_default_timezone_set('Africa/Tunis');
          $time = gmmktime();
          $format = 'd/m/Y';
          $timeday=date("H:i:s", $time);
          $dateday=date($format, $time);
      
          // $herefinal=date("H:i:s",strtotime('08:59:20')+strtotime('01:00:00'));
          // echo $herefinal;
         // $heurematins=strtotime($heurematin)-strtotime("00:00:00");
         // $timematin =date("H:i:s",strtotime($heurematin)+strtotime("01:00:00"));
          $secs = strtotime($heurematin)-strtotime("00:00:00");
$timematin = date("H:i:s",strtotime('01:00:00')+$secs);
         // echo 'm'.$timematin;
          $timeapresmidi = date("H:i:s",strtotime($heureapresmidi)+strtotime("01:00:00"));
          $secs = strtotime($heureapresmidi)-strtotime("00:00:00");
$timeapresmidi = date("H:i:s",strtotime('01:00:00')+$secs);
 //echo'a'.$timeapresmidi;
         // $timematinpause = "1:00:00";
        // $time = "15:00";
//         $status = "15:01:00";
// $time = "15:00:00";
// if(strtotime($time) <= strtotime($status))
// {
// echo 'yesstrue';
// } 
if(strtotime($timeday) <= strtotime($timematin))
{
echo 'true';


        
            
            $retard =$this->documentManager->getRepository(Retard::class)->findBy(['jour' =>$dateday]);
            foreach($retard as $temps) 
            {
                $time=$temps->getNbrheure();
               // echo'id'.$temps->getIdEmp();
                //echo'timeenretard'.$time;
                //echo'retardaccep'.$retardmatin;
                if(strtotime($time)>strtotime($retardmatin)){
                   // echo '**********';
                    $employe = $this->documentManager->createQueryBuilder('\App\Document\Employe')
        ->field('idemp')->equals($temps->getIdEmp())
       ->getQuery()
        ->getSingleResult(); 
        $date1=strtotime($time);
        $date2=strtotime($retardmatin);
        $x=abs($date1-$date2);
        $h=floor(abs($date1-$date2)/(60*60));
        
       
        $m=floor(($x-$h*60*60)/60);
        $s=floor($x-$h*60*60 - $m*60);
        $herinstante=$h.':'.$m.':'.$s;
       // echo 'lll'.$herinstante;
        $heureretard=strtotime($herinstante)-strtotime("00:00:00");
        $date=date("H:i:s", strtotime('00:00:00')+$heureretard);
        //echo 'ttt'.$date;
        $userRh = $this->documentManager->createQueryBuilder('\App\Document\Employe')
        ->field('roles')->equals('ROLE_RH')
        ->field('societe')->equals($temps->getNumrosociete())
        ->getQuery()
        ->getSingleResult();
        //echo $employe->getNom();
                  //  $var='smtp://ahlemthameur0%40gmail.com:AH13406574@smtp.gmail.com:465';
                    $loader = new FilesystemLoader('C:\wamp64\www\projet\templates');
                    $twig = new Environment($loader);
                    $transport = Transport::fromDsn($var);
            $mailer = new Mailer($transport); 
            $email = (new TemplatedEmail())
                      ->from('ahlemthameur0@gmail.com')
                        ->to('thameuramel27@gmail.com')
                        ->addto('amanithameur132@gmail.com')
                        ->subject('avertissement pour retards')
                        ->htmlTemplate('emails/emailalerte.html.twig')
                        ->context([
                            'heurereard' =>$date,
                            'nom'=>$employe->getNom(),
                            'url'=>$url
                           
                       ]);
                        $renderer = new BodyRenderer($twig);
                        $renderer->render($email);
               $mailer->send($email); 
             //echo 'envoy';
                

            }}

    }
    
    else if(strtotime($timeday) <= strtotime($timeapresmidi)){
      if($senstravail=="double sens"){echo 'double sens';
        $employes = $this->documentManager->getRepository(Employe::class)->findAll();
        foreach($employes as $employer){
            
          
                    $hr='00:00:00';
                    $listdate = $this->documentManager->getRepository(ChektTimeIn::class)->findby(array('idEmp' =>$employer->getIdemp(),'date' =>$dateday),array('temps'=>'DESC'),1,0);
                    foreach ($listdate as $lst)  {
                //    echo 'id'.$lst->getIdEmp();
                  //echo 'nom'.$employer->getNom();
                // echo 'temps'.$lst->getTemps();
                     $date1= strtotime($lst->getTemps());
                        
                     $date2 = strtotime($heureapresmidi);
                     if($date1>$date2){
                     $x=abs($date1-$date2);
                     $h=floor(abs($date1-$date2)/(60*60));
                     
                    
                     $m=floor(($x-$h*60*60)/60);
                     $s=floor($x-$h*60*60 - $m*60);
                       $herinstante=$h.':'.$m.':'.$s;
                       $secs=strtotime($herinstante)-strtotime("00:00:00");
                       $herefinal=date("H:i:s",strtotime($hr)+$secs);
                       $hr=$herefinal ;
                       if(strtotime($herefinal)>strtotime($retardapresmidi)){

$date1=strtotime($herefinal);
        $date2=strtotime($retardapresmidi);
        $x=abs($date1-$date2);
        $h=floor(abs($date1-$date2)/(60*60));
        
       
        $m=floor(($x-$h*60*60)/60);
        $s=floor($x-$h*60*60 - $m*60);
        $herinstante=$h.':'.$m.':'.$s;
        $heureretard=strtotime($herinstante)-strtotime("00:00:00");
       //$date=date("H:i:s",$heureretard);
       $date=date("H:i:s", strtotime('00:00:00')+$heureretard);

                      
                       }
                       $userRh = $this->documentManager->createQueryBuilder('\App\Document\Employe')
                       ->field('roles')->equals('ROLE_RH')
                       ->field('societe')->equals($lst->getNumeroSocietes())
                       ->getQuery()
                       ->getSingleResult();
                       $loader = new FilesystemLoader('C:\wamp64\www\projet\templates');
                    $twig = new Environment($loader);
                    $transport = Transport::fromDsn($var);
            $mailer = new Mailer($transport); 
            $email = (new TemplatedEmail())
                      ->from('ahlemthameur0@gmail.com')
                        ->to('thameuramel27@gmail.com')
                        ->addto('amanithameur132@gmail.com')
                        ->subject('avertissement pour retards')
                        ->htmlTemplate('emails/emailalerte.html.twig')
                        ->context([
                            'heurereard' =>$date,
                            'nom'=>$employer->getNom(),
                            'url'=>$url
                           
                       ]);
                        $renderer = new BodyRenderer($twig);
                        $renderer->render($email);
                $mailer->send($email); 
                    }
                       else{$hr='00:00:00';}}
                      // echo 'nom'.$employer->getNom();
                      // echo 'date'.$hr;
                    }
        
        echo 'falsetst';
    }}
        return 0;
    }
    public function gettimeparjour($listedateersss){
      $employes = $this->documentManager->getRepository(Employe::class)->findAll();
      foreach($employes as $employer){
          foreach($listedateersss as $d){
          if($d['id' ]== $employer->getIdemp()){
              foreach($d['liste'] as $jour ){
                  $hr='00:00:00';
                //  $listdatetest = $this->documentManager->getRepository(Retard::class)->finOnedby(['idEmp' =>$employer->getIdemp(),'date' => $jour]);

                  $listdatetest = $this->documentManager->createQueryBuilder('\App\Document\Retard')
                  ->field('idEmp')->equals($employer->getIdemp())
                  ->field('jour')->equals($jour)
                  ->getQuery()
                  ->getSingleResult();
                  if($listdatetest == null){
  echo 'notexiste';

                  $listdate = $this->documentManager->getRepository(ChektTimeIn::class)->findby(array('idEmp' =>$employer->getIdemp(),'date' => $jour),array('temps'=>'ASC'),1,0);
                  foreach ($listdate as $lst)  {
              //    echo 'id'.$lst->getIdEmp();
              //    echo 'date'.$lst->getDate();
               echo 'temps'.$lst->getTemps();
                   $date1= strtotime($lst->getTemps());
                   $paramtregeneral = $this->documentManager->getRepository(ParametreGenerale::class)->findAll();
          
                   foreach($paramtregeneral as $paramtregenerals){
                      $heurematin= $paramtregenerals->getHeurematin();
                     echo 'heurematin'.$heurematin;
                   } 
                   $date2 = strtotime($heurematin);
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
                     $this->documentManager->persist($nbrheure);
                     $this->documentManager->flush();
                

                  }}
                  else{echo 'exist';}
              }
          }
      }}

  }
}
