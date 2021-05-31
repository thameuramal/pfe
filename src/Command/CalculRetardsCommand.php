<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Document\ParametreGenerale; 

use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Societes;
 
use App\Document\Employe;
use App\Document\Retard;
use App\Document\ChektTimeIn;
use App\Document\ChektTimeOut;
class CalculRetardsCommand extends Command
{
    protected static $defaultName = 'CalculRetards';
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

    }}