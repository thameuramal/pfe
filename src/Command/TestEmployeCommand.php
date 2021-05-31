<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Document\Employe;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
class TestEmployeCommand extends Command
{   
    private $documentManager;
    protected static $defaultName = 'testEmploye';
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $roles[] = 'EMPLOYER';
        $dataBaseName = $this->container->getParameter('namedbacces');
        $conx=odbc_connect($dataBaseName,"","");
        $sql = "SELECT * FROM USERINFO";
      $rs = odbc_exec($conx,$sql);
      
      while(odbc_fetch_row($rs)){
        $nom =odbc_result($rs,'Name');
        $matricule =odbc_result($rs,'CardNo');
        $numrosociete =odbc_result($rs,'DEFAULTDEPTID');
        $id =odbc_result($rs,'USERID');
        echo"id".$numrosociete;
        echo"name".$nom;
        echo"<br/>";
        $employe = new Employe();
        $employe ->setNom($nom);
        $employe ->setSociete($numrosociete);
        $employe ->setMatricule($matricule);
        $employe ->setIdemp($id);
      
        
        $employe ->setRoles($roles); 
        $this->documentManager->persist($employe);
        $this->documentManager->flush();
        
       }

        return 0;
    }
}
