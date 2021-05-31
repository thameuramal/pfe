<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Societes;
use App\Document\HeureTravaille;
use App\Document\Employe;

use App\Document\ChektTimeIn;
use App\Document\ChektTimeOut;
class CalculTempsTrvailCommand extends Command
{
    protected static $defaultName = 'CalculTempsTrvail';
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
        $count =  $this->documentManager->createQueryBuilder('App\Document\HeureTravaille')->count()->getQuery()->execute();
        if($count!=0){
            // if($count ==0){
               
          
           // echo 'not videe';
           $this->documentManager->getSchemaManager()->dropDocumentCollection(HeureTravaille::class);
           $this->gettimeparjour();
       
        }
        else{
            //echo 'vide';
            $this->gettimeparjour();
          
             }
        


        return 0;
    }
    public function gettimeparjour(){
        $this->documentManager->getSchemaManager()->dropDocumentCollection(HeureTravaille::class);
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
      
        
        $listdate = $this->documentManager->getRepository(ChektTimeIn::class)->findAll();
    
              
        foreach ($listdate as $lst)  {
          if(! in_array($lst->getDate() , $listedatee)){
               array_push($listedatee,$lst->getDate()); }
          
          
              
               
          $listedatee=array_unique($listedatee);}
    
          $listdate =  $this->documentManager->getRepository(ChektTimeIn::class)->findAll();
      
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
          
        
    
    
     
            $employes = $this->documentManager->getRepository(Employe::class)->findAll();
            
            foreach($employes as $employer){
         
                 foreach($listedateersss as $d){
                    if($d['id' ]== $employer->getIdemp()){
                
                    foreach($d['liste'] as $date ){
              
            $tempsexist = $this->documentManager->getRepository(HeureTravaille::class)->findBy(['idEmp' =>$employer->getIdemp() , 'date' => $date]);
             if($tempsexist != null){
                 echo 'existe';
             } else {echo 'not existe';}           
                    $k=0 ;
                   $i=0;
                   $hr='00:00:00';
                
                
           
                $tmpout = $this->documentManager->getRepository(ChektTimeOut::class)->findBy(array('idEmp' =>$employer->getIdemp() , 'date' => $date),array('temps'=>'desc'));
                $tmpin = $this->documentManager->getRepository(ChektTimeIn::class)->findBy(array('idEmp' =>$employer->getIdemp() , 'date' => $date),array('temps'=>'desc'));
    
               
                foreach($tmpout as $outdate){
                    $k++;
                    
                     $outputdate[$k]=$outdate->getTemps();
                     
                  
                    
                }
                foreach($tmpin as $indate){
                    $i++;
                   
                    $inputdate[$i]=$indate->getTemps();
                    
                  
                    
                } 
              
             while($i!=0 && $k!=0){
                 
                 
                    $date1= strtotime($outputdate[$k]);
                    
                     $date2 = strtotime($inputdate[$i]);
               
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
                $k-- ;
                
                 } 
                // echo 'date'.$employer->getIdemp();
            if($employer->getIdemp()!=''){
            $nbrheure = new HeureTravaille();
            $nbrheure->setIdEmp($employer->getIdemp());
            $nbrheure->setNumrosociete($employer->getSociete());
            $nbrheure->setJour($date);
            $nbrheure->setNbrheure($hr);
            $this->documentManager->persist($nbrheure);
            $this->documentManager->flush();
            }
            
                
                            
       
        
             }
    
                  
        }}
        }
       }
         
}
