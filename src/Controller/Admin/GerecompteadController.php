<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Document\Employe;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport ;
use Symfony\Component\Mailer\Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\HttpFoundation\Request;
use App\Document\ParametrageEmail; 
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Twig\Environment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Twig\Loader\FilesystemLoader;
class GerecompteadController extends AbstractController
{
    /**
     * * @IsGranted("ROLE_ADMIN")
     * @Route("/manage/admin/profile", name="manageadminprofile")
     */ 
    public function index(DocumentManager $dm,Request $request,UserPasswordEncoderInterface $passwordEncoder): Response 
    { $user = $this->getUser();
        $tstlongueur='false';
        $tstmajscule='false';
        $tstminscule='false';
        $testchiffre='false';
        $testcaratspecifique='false';
      
        
        if($request->request-> count()>0){
           
            $passwordad=$request->request->get('password');
          //  echo 'password'.$passwordad;
            $longueur=strlen($passwordad);
            $majuscule='AZERTYUIOPQSDFGHJKLMWXCVBN';
            $lengthmajs=strlen($majuscule);
            $minuscule='azertyuiopqsdfghjklmwxcvbn';
            
            $chiffre='123456789';
            $lengtchiffre=strlen($chiffre);
            $cartrspecifie='!@#$%&*()_+/<>?[]{}\|';
            $lengtcartrspecifie=strlen($cartrspecifie);

            if(($longueur>6)){
                $tstlongueur='true';
              //  echo 'truelongeure*****';
            }
for ($i=0; $i<$longueur; $i++) {
    
 for ($j=0; $j<$lengthmajs; $j++) {
  if($passwordad[$i]==$majuscule[$j]){
    $tstmajscule='true';
   // echo 'truemajscule***';
   }
  if($passwordad[$i]==$minuscule[$j]){
    $tstminscule='true';
   // echo 'trueminscule***';
    }

    }
    for ($j=0; $j<$lengtchiffre; $j++) {
        if($passwordad[$i]==$chiffre[$j]){
          $testchiffre='true';
         // echo 'true chiffre****';
         }
        
      
          }
          for ($j=0; $j<$lengtcartrspecifie; $j++) {
            if($passwordad[$i]==$cartrspecifie[$j]){
              $testcaratspecifique='true';
              //echo 'truecaractre*****';
             }
            
          
              }


  
}
//echo 'l'.$tstlongueur.'ma'.$tstmajscule.'mi'.$tstminscule.'ch'.$testchiffre.'cart'.$testcaratspecifique;
if( $tstlongueur=='true' && $tstmajscule='true' && $tstminscule== 'true' && $testchiffre=='true' && $testcaratspecifique=='true')
{
    //echo 'entretrue';
$mail=$request->request->get('email');

        $employes = $dm->getRepository(Employe::class)->findBy(['roles' =>'ROLE_ADMIN']);
        foreach($employes as $employer){
            $employer ->setNom($request->request->get('nom'));
            $employer->setEmail($mail);
            $employer->setDateDeNaissance($passwordad);
            $employer->setPassword($passwordEncoder->encodePassword(
                $employer,$passwordad));

           
        }
        $dm->flush();
        $paramtre = $dm->getRepository(ParametrageEmail::class)->findAll();
        foreach($paramtre as $parm){
            $host=$parm->getHost();
            
            $mail=$parm->getEmail();
            $password=$parm->getPassword();
            $port=$parm->getPort();
           // $url=$parm->getUrl();
        } 
        $mailmodifier=str_replace("@","%40",$mail);
         $var='smtp://'.$mailmodifier.':'.$password.'@'.$host.':'.$port;

    // $var='smtp://ahlemthameur0%40gmail.com:AH13406574@smtp.gmail.com:465';
        $loader = new FilesystemLoader('C:\wamp64\www\projet\templates');
$twig = new Environment($loader);
$transport = Transport::fromDsn($var);
$mailer = new Mailer($transport);
$email = (new TemplatedEmail())
          ->from('ahlemthameur0@gmail.com')
            ->to('thameuramel27@gmail.com')
            ->subject('Nouvelle Compte')
            ->htmlTemplate('emails/emailnewcomptead.html.twig')
            ->context([
                'login'=>$mail,
               'password' =>'ml8$ldm@t'
             
              // 'url'=>$url
                
           ]);
            $renderer = new BodyRenderer($twig);
            $renderer->render($email);
  //  $mailer->send($email);
    
  $this->addFlash('modifier', 'Les paramètres ont été modifiés');
    }
    else{

    $this->addFlash('fautsaisir','Veuillez choisir un mot de passe plus sûr');

    }}
    $employes = $dm->getRepository(Employe::class)->findBy(['roles' =>'ROLE_ADMIN']);
        foreach($employes as $employer){
            $emailad=$employer->getEmail();
            $nomad=$employer-> getNom();
            
            //$passwordad=$employer->getPassword();
            //echo 'pss'.$passwordad;
        }

        
        return $this->render('Admin/gerecomptead/index.html.twig', [
            'controller_name' => 'GerecompteadController','nom'=>$nomad,'email'=>$emailad 
        ]);
    }
}
