<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use JMS\JobQueueBundle\Console\CronCommand;
use App\Document\Societes;


class TestSocietesCommand extends Command 
{
    protected static $defaultName = 'testSocietes';
    protected static $defaultDescription = 'Add a short description for your command';


    private $documentManager;
  
    
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dataBaseName = $this->container->getParameter('namedbacces');
        $conx=odbc_connect($dataBaseName,"","");
        $sql = "SELECT * FROM DEPARTMENTS";
      $rs = odbc_exec($conx,$sql);
      
      while(odbc_fetch_row($rs)){
        $nom =odbc_result($rs,'DEPTNAME');
        $numro =odbc_result($rs,'DEPTID');
        echo"id".$numro;
        echo"name".$nom;
        echo"<br/>";
        $Societs = new Societes();
        $Societs ->setNom($nom);
        $Societs ->setNumero($numro);
        $this->documentManager->persist($Societs);
        $this->documentManager->flush();
      }
        
       return 0 ;
    }
}
