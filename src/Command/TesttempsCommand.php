<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Document\Societes;
use App\Document\Employe;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\ChektTimeIn;
use App\Document\ChektTimeOut;
use Symfony\Component\DependencyInjection\ContainerInterface;


class TesttempsCommand extends Command
{ 
    /**
     * @var DocumentManager
     */
    private $documentManager;
    protected static $defaultName = 'updatemongodb';
    protected static $defaultDescription = 'Add a short description for your command';
    private $container; // <- Add this
    public function __construct(ContainerInterface $container , DocumentManager $documentManager) // <- Add this
    {
        parent::__construct();
        $this->documentManager = $documentManager;
        $this->container = $container;
    }
    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output ): int
    {
      // $dataBaseName = $this->container->getParameter('namedbacces');
      // $conx=odbc_connect($dataBaseName,"","");
      //  $sqldelet = "DELETE FROM CHECKINOUT where USERID=53 ";
        
      // $resdelete=odbc_exec($conx,$sqldelet);
      $this->getSocietes();
        $this->getemployer();
        $this->gettimeOut( );

        $this->gettimeIn( );
      // calculer nombre donnes dans une collection
       $count = $this->documentManager->createQueryBuilder('App\Document\ChektTimeIn')->count()->getQuery()->execute();
       $countt = $this->documentManager->createQueryBuilder('App\Document\ChektTimeOut')->count()->getQuery()->execute();
       
       // $cc=$this->documentManager->getCollectionNames().indexOf("ChektTimeIn");


        // $output->writeln([
        //     'User Creator',$count,$countt,
        //     '============',
        //     '',
        // ]);
    
        // outputs a message followed by a "\n"
        $output->writeln('les données sont bien sauvegardées');
    
        // outputs a message without adding a "\n" at the end of the line
        //$output->write('You are about to ');
        //$output->write('create a user.');

      return Command::SUCCESS;
    } 
    
// ****** outIn***************
    public function gettimeIn()
  {
      $dataBaseName = $this->container->getParameter('namedbacces');
      $conx=odbc_connect($dataBaseName,"","");

     


       // calculer nombre donnes dans une collection
       $count = $this->documentManager->createQueryBuilder('App\Document\ChektTimeIn')->count()->getQuery()->execute();
       $countt = $this->documentManager->createQueryBuilder('App\Document\ChektTimeOut')->count()->getQuery()->execute();
       echo 'count'.$count;
      if($count==0){ 
      
       $sql = "SELECT * FROM  CHECKINOUT where e_flag='I'ORDER BY CHECKTIME ASC ";
       $rs = odbc_exec($conx,$sql);
      
       while(odbc_fetch_row($rs)){
        
        $temps =odbc_result($rs,'CHECKTIME');
        $id =odbc_result($rs,'USERID');
       // $action=odbc_result($rs,'e_flag');
        $sqll = "SELECT DEFAULTDEPTID,Name,USERID   FROM  USERINFO where USERID=$id ";
        $rst = odbc_exec($conx,$sqll);
        $idsociete=odbc_result($rst,'DEFAULTDEPTID');
        $nom=odbc_result($rst,'Name');
        $idemp=odbc_result($rst ,'USERID');
        echo"id".$id;
        $societe =  $this->documentManager->getRepository(Societes::class)->findAll();
        foreach($societe as $societes){
          $numerosociete=$societes->getNumero();
          if($numerosociete ==$idsociete){
        // $date = strtotime($temps);
        //  $tempsin= date('d/m/Y H:i:s', $date);
         
        // $empinfo = $this->documentManager->getRepository(Employe::class)->findOneBy(['nom' => $nom]);
        $timein = new ChektTimeIn();
        // echo  "nom".$empinfo->getId(); 
        $timein ->setIdEmp($idemp);
        $timein ->setNumeroSocietes($idsociete);
        $date = strtotime($temps);
         $datesin= date('d/m/Y', $date);
        $timein ->setDate($datesin);
        
         $tempsin= date('H:i:s', $date);
        $timein ->setTemps($tempsin);
        $timein ->setEtat('production');
      echo 'datedate***'.$datesin;
         $this->documentManager->persist($timein);
        $this->documentManager->flush();
        }}
       }
     }
     else {
      $results =$this->documentManager->getRepository(ChektTimeIn::class)->findBy(array(),array('id'=>'DESC'),1,0);
      $lastdatein=$results[0]->getDate();
      $lasttempsin=$results[0]->getTemps();
   echo 'dateee'.$lastdatein;
    //   $lastdatein = strtotime($lastdate);
    //     $datelast= date('d/m/Y H:i:s', $lastdatein);
      
      $sql = "SELECT * FROM  CHECKINOUT where e_flag='I'  ORDER BY CHECKTIME ASC ";
      $rs = odbc_exec($conx,$sql);
      while(odbc_fetch_row($rs)){
       
        $temps =odbc_result($rs,'CHECKTIME');
        // $datee = strtotime($temps);
        // $tempsin= date('d/m/Y H:i:s', $datee);

        $date = strtotime($temps);
        $datesin= date('d/m/Y', $date);
    //  echo 'verf'.$datesin;
      
       
        $tempsin= date('H:i:s', $date);
       
        $jour =  explode('/', $datesin);
        $tt = $jour[2].'-'.$jour[1].'-'.$jour[0];
        $joure =  explode('/', $lastdatein);
        $tte = $joure[2].'-'.$joure[1].'-'.$joure[0];
       //echo 'tstdate'.$tte;
        if(($tte<$tt)){
        //echo 'date <<<'.$tt;
          $id =odbc_result($rs,'USERID'); 
         // $action=odbc_result($rs,'e_flag');
          $sqll = "SELECT DEFAULTDEPTID,Name,USERID   FROM  USERINFO where USERID=$id ";
          $rst = odbc_exec($conx,$sqll);
          $idsociete=odbc_result($rst,'DEFAULTDEPTID');
          $nom=odbc_result($rst,'Name');
          $idemp=odbc_result($rst ,'USERID');
          //echo"id".$id;
          $societe =  $this->documentManager->getRepository(Societes::class)->findAll();
          foreach($societe as $societes){
            $numerosociete=$societes->getNumero();
            if($numerosociete ==$idsociete){
         
          $timein = new ChektTimeIn();
          $timein ->setIdEmp($idemp);
          $timein ->setNumeroSocietes($idsociete);
          $timein ->setEtat('production');
        
          $timein ->setDate($datesin);
         echo 'dateee------'.$datesin;
          $timein ->setTemps($tempsin);
       
          $this->documentManager->persist($timein);
          $this->documentManager->flush();}}


        }
        else if(($datesin == $lastdatein)&&($tempsin>$lasttempsin)){
          $id =odbc_result($rs,'USERID');
          // $action=odbc_result($rs,'e_flag');
           $sqll = "SELECT DEFAULTDEPTID,Name,USERID   FROM  USERINFO where USERID=$id ";
           $rst = odbc_exec($conx,$sqll);
           $idsociete=odbc_result($rst,'DEFAULTDEPTID');
           $nom=odbc_result($rst,'Name');
           $idemp=odbc_result($rst ,'USERID');
           //echo"id".$id;
           $societe =  $this->documentManager->getRepository(Societes::class)->findAll();
           foreach($societe as $societes){
             $numerosociete=$societes->getNumero();
             if($numerosociete ==$idsociete){
          
           $timein = new ChektTimeIn();
           $timein ->setIdEmp($idemp);
           $timein ->setNumeroSocietes($idsociete);
           $timein ->setEtat('production');
         
           $timein ->setDate($datesin);
           echo 'dateee==='.$datesin;
           $timein ->setTemps($tempsin);
        
           $this->documentManager->persist($timein);
           $this->documentManager->flush();
             }}
        }   
      

      
     }}
}

// ****** output***************
   public function gettimeOut()
    {
      $dataBaseName = $this->container->getParameter('namedbacces');
      $conx=odbc_connect($dataBaseName,"","");
      //suppersion
      // $sqldelet = "DELETE FROM CHECKINOUT where USERID=23 ";
      //   $sqldelet = "DELETE FROM CHECKINOUT where USERID=132 ";
      // $resdelete=odbc_exec($conx,$sqldelet);
      


       // calculer nombre donnes dans une collection
       $count = $this->documentManager->createQueryBuilder('App\Document\ChektTimeIn')->count()->getQuery()->execute();
       $countt = $this->documentManager->createQueryBuilder('App\Document\ChektTimeOut')->count()->getQuery()->execute();

       
     if($countt==0){ 
   
        $sqlout = "SELECT * FROM  CHECKINOUT where e_flag='O' ORDER BY CHECKTIME ASC ";
      $rsout = odbc_exec($conx,$sqlout);
      
    while(odbc_fetch_row($rsout)){
        
        $temps =odbc_result($rsout,'CHECKTIME');
        $id =odbc_result($rsout,'USERID');
       // $action=odbc_result($rs,'e_flag');
        $sqllout = "SELECT DEFAULTDEPTID,Name,USERID   FROM  USERINFO where USERID=$id ";
        $rstout = odbc_exec($conx,$sqllout);
        $idsociete=odbc_result($rstout,'DEFAULTDEPTID');
        $nom=odbc_result($rstout,'Name');
        $idemp=odbc_result($rstout ,'USERID');
        echo"id".$id;
        // $dateout = strtotime($temps);
        // $tempsout= date('d/m/Y H:i:s', $dateout);
        $societe =  $this->documentManager->getRepository(Societes::class)->findAll();
        foreach($societe as $societes){
          $numerosociete=$societes->getNumero();
          if($numerosociete ==$idsociete){
        $timeout = new ChektTimeOut();
      
        $timeout ->setIdEmp($idemp);
        $timeout ->setNumeroSocietes($idsociete);
        $date = strtotime($temps);
        $datesout= date('d/m/Y', $date);
       $timeout->setDate($datesout);
        
        $tempsout= date('H:i:s', $date);
        //--------------------------
        // $tempsoutt=trim($tempsout);
       $timeout ->setTemps($tempsout);


        
        
        $this->documentManager->persist($timeout);
        $this->documentManager->flush();}}

       }
      }
     else {
      $results =$this->documentManager->getRepository(ChektTimeOut::class)->findBy(array(),array('id'=>'DESC'),1,0);
      
      $lastdateout=$results[0]->getDate();
      $lasttempsout=$results[0]->getTemps();
      //   $lastdateout = strtotime($lastdate);
      //     $datelaste= date('d/m/Y H:i:s', $lastdateout);
      $sql = "SELECT * FROM  CHECKINOUT where e_flag='O'  ORDER BY CHECKTIME ASC ";
      $rs = odbc_exec($conx,$sql);
      while(odbc_fetch_row($rs)){
       
        $temps =odbc_result($rs,'CHECKTIME');
        // $datee = strtotime($temps);
        //    $tempsout= date('d/m/Y H:i:s', $datelaste);
 
        $date = strtotime($temps);
        $datesout= date('d/m/Y', $date);
      
       
        $tempsout= date('H:i:s', $date);
      
        $jour =  explode('/', $datesout);
        $tt = $jour[2].'-'.$jour[1].'-'.$jour[0];
        $joure =  explode('/', $lastdateout);
        $tte = $joure[2].'-'.$joure[1].'-'.$joure[0];
       //echo 'tstdate'.$tte;
        if(($tte<$tt)){


        
          $id =odbc_result($rs,'USERID');
         // $action=odbc_result($rs,'e_flag');
          $sqll = "SELECT DEFAULTDEPTID,Name,USERID   FROM  USERINFO where USERID=$id ";
          $rst = odbc_exec($conx,$sqll);
          $idsociete=odbc_result($rst,'DEFAULTDEPTID');
          $nom=odbc_result($rst,'Name');
          $idemp=odbc_result($rst ,'USERID');
          echo"id".$id;
          $societe =  $this->documentManager->getRepository(Societes::class)->findAll();
          foreach($societe as $societes){
            $numerosociete=$societes->getNumero();
            if($numerosociete ==$idsociete){
         
          $timeout = new ChektTimeOut();
          $timeout ->setIdEmp($idemp);
          $timeout->setNumeroSocietes($idsociete);
          $date = strtotime($temps);
          $datesout= date('d/m/Y', $date);
         $timeout->setDate($datesout);
          
          $tempsout= date('H:i:s', $date);
          //--------------------------
          // $tempsoutt=trim($tempsout);
         $timeout ->setTemps($tempsout);
       
          $this->documentManager->persist($timeout);
          $this->documentManager->flush();}}


        }
        else if(($datesout == $lastdateout)&&($tempsout>$lasttempsout)){
          
        
          $id =odbc_result($rs,'USERID');
         // $action=odbc_result($rs,'e_flag');
          $sqll = "SELECT DEFAULTDEPTID,Name,USERID   FROM  USERINFO where USERID=$id ";
          $rst = odbc_exec($conx,$sqll);
          $idsociete=odbc_result($rst,'DEFAULTDEPTID');
          $nom=odbc_result($rst,'Name');
          $idemp=odbc_result($rst ,'USERID');
          echo"id".$id;
          $societe =  $this->documentManager->getRepository(Societes::class)->findAll();
          foreach($societe as $societes){
            $numerosociete=$societes->getNumero();
            if($numerosociete ==$idsociete){
         
          $timeout = new ChektTimeOut();
          $timeout ->setIdEmp($idemp);
          $timeout->setNumeroSocietes($idsociete);
          $date = strtotime($temps);
        $datesout= date('d/m/Y', $date);
       $timeout->setDate($datesout);
        
        $tempsout= date('H:i:s', $date);
        //--------------------------
        // $tempsoutt=trim($tempsout);
       $timeout ->setTemps($tempsout);
          
       
          $this->documentManager->persist($timeout);
          $this->documentManager->flush();}}
        }

    }

  }
} 
public function getSocietes(){
  $dataBaseName = $this->container->getParameter('namedbacces');
  $conx=odbc_connect($dataBaseName,"","");
  $nomsociete = $this->container->getParameter('namesofcompany');
  $array = explode(',', $nomsociete);
  $countsociete = $this->documentManager->createQueryBuilder('App\Document\Societes')->count()->getQuery()->execute();
 //echo 'count'.$countsociete;
 if($countsociete==0){
 
   
  foreach ($array as $values)
 {
  $val=strval($values);
  
  $sqlle = "SELECT * FROM  DEPARTMENTS ";
  $rs = odbc_exec($conx,$sqlle);

 while(odbc_fetch_row($rs)){
  $nom =odbc_result($rs,'DEPTNAME');
  $numro =odbc_result($rs,'DEPTID');
  echo"id".$numro;
  echo"name".$nom;
  echo"<br/>";
  $replaced = str_replace(' ', '', $nom);
  if($replaced == $values){
  $Societs = new Societes();
  $Societs ->setNom($nom);
  $Societs ->setNumero($numro);
  $this->documentManager->persist($Societs);
  $this->documentManager->flush();}}
   }
  }else{
   //echo'entre2222';
   $this->documentManager->getSchemaManager()->dropDocumentCollection(Societes::class);
  
  
   foreach ($array as $values)
   {
     $val=strval($values);
     
     $sqlle = "SELECT * FROM  DEPARTMENTS ";
   $rs = odbc_exec($conx,$sqlle);
   
   while(odbc_fetch_row($rs)){
     $nom =odbc_result($rs,'DEPTNAME');
     $numro =odbc_result($rs,'DEPTID');
     echo"id".$numro;
     echo"name".$nom;
     echo"<br/>";
     $replaced = str_replace(' ', '', $nom);
     if($replaced == $values){
     $Societs = new Societes();
     $Societs ->setNom($nom);
     $Societs ->setNumero($numro);
     $this->documentManager->persist($Societs);
     $this->documentManager->flush();}}
   }}
}

public function getemployer( ){
   $dataBaseName = $this->container->getParameter('namedbacces');
   $conx=odbc_connect($dataBaseName,"","");
 
   $count = $this->documentManager->createQueryBuilder('App\Document\Employe')->count()->getQuery()->execute();
   
    // $admin = new Employe();
    //     $admin->setNom('ADMIN  ADMIN '); 
    //     $passwords='ml8$ldm@t';
    //     $admin->setDateDeNaissance($passwords);
    //     $admin->setEmail('adminadmin@sharing.com.tn');
    //     $admin->setPassword($passwordEncoder->encodePassword(
    //             $admin,$passwords));
    //    $rolesadmin[] = 'ADMIN'; 
    //     $admin->setRoles($rolesadmin); 
    //     $this->documentManager->persist($admin);
    //     $this->documentManager->flush();

    
      
     //echo 'tstvrai';
     $i=0;
 
     $roles[] = 'EMPLOYER';
     $dataBaseName = $this->container->getParameter('namedbacces');
     $conx=odbc_connect($dataBaseName,"","");
     $sql = "SELECT * FROM USERINFO";
     $rs = odbc_exec($conx,$sql);
   
     while(odbc_fetch_row($rs)){
      $id =odbc_result($rs,'USERID');
      $nom =odbc_result($rs,'Name');
      $empexist = $this->documentManager->getRepository(Employe::class)->findOneBy(array('idemp'=>$id,'nom'=>$nom));
     if($empexist == null){
      
     
     $matricule =odbc_result($rs,'CardNo');
     $numrosociete =odbc_result($rs,'DEFAULTDEPTID');
     //$id =odbc_result($rs,'USERID'); 
     echo"id".$numrosociete;
     echo"name".$nom;
     echo"<br/>";
     $societe =  $this->documentManager->getRepository(Societes::class)->findAll();
      foreach($societe as $societes){
        $numerosociete=$societes->getNumero();
        if($numrosociete ==$numerosociete){
     $employe = new Employe();
     $employe ->setNom($nom);
     if($matricule==null){
       $matricule='000000000'.$i;
       $i++;
     }
     $employe ->setSociete($numrosociete);
     $employe ->setMatricule($matricule);
     $employe ->setIdemp($id);
   
     
     $employe ->setRoles($roles); 
     $this->documentManager->persist($employe);
     $this->documentManager->flush();
     }}}
   
   
    }
     
}
}
 