<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Document\Employe;
use Symfony\Component\Security\Core\Util\SecureRandom;
use Symfony\Component\Mailer\MailerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Mailer\Transport ;
use Symfony\Component\Mailer\Mailer;

use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Mime\Email;
use App\Document\ParametrageEmail; 
use App\Document\ParametreGenerale; 
class NewcompterController extends AbstractController
{
    /**
     * @Route("/newaccount", name="newaccount")
     */
     public function index(DocumentManager $dm ,Request $request,MailerInterface $mailer,UserPasswordEncoderInterface $passwordEncoder): Response
    {  
        //ajouter un admin 
    //     $employe = new Employe();
    //     $employe ->setNom('ADMIN  ADMIN ');
    //    //$employe ->setIdemp('00');
    //     //$employe ->setMatricule('0000');
    //     //$employe ->setMatricule('0000');
    //    $roles[] = 'ROLE_ADMIN'; 
    //     $employe ->setRoles($roles); 
    //     $dm->persist($employe);
    //     $dm->flush();
    //     $employes = $dm->getRepository(Employe::class)->findBy(['nom' =>'ADMIN  ADMIN ']);
    //  foreach($employes as $employer){
    //     $passwords='ml8$ldm@t';
    //     $employer->setEmail('adminadmin@sharing.com.tn');
    //         $employer->setDateDeNaissance($passwords);
    //     $employer->setPassword($passwordEncoder->encodePassword(
    //         $employer,$passwords));}
    //     //$dm->persist($employe);
    //     $dm->flush();
        
        
        $employe = $dm->getRepository(Employe::class)->findAll();
        $output=[]; 
        $domaines=[];
        $password='';
        $correctforme='false';
        $permitted_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%&*()_+/<>?[]{}\|';
    //    foreach($employe as $emp)
    //    {
        //    $data='Nom'.$emp->getNom().'Matricule'.getMatricule();
        $output=$employe;
        dump($request);
        //get domaines
        $paramtregeneral =  $dm->getRepository(ParametreGenerale::class)->findAll();
            
            foreach($paramtregeneral as $paramtregenerals){
                $domaines=$paramtregenerals->getDomaines();
                $url=$paramtregenerals->getUrl();
            } 
            
            // foreach($domaines as $dom=>$k){
            //     echo 'dom'.$dom;
            //     if($k!=null){
            //     echo 'notvide';
            //     foreach($k as $kk){
            //     echo'nom'.$kk;
            // }}
            // }

        $emailemp=$request->get('email');
        
        if($request->request->count()>0){
            $finemail=explode('@', $emailemp)[1];
        if($emailemp !=''){
            foreach($domaines as $dom=>$k){
                
                if($k!=null){
                
                foreach($k as $kk){
                    if($finemail!=$kk){
                       // echo 'diff';
                        //$this->addFlash('incorrectemail', 'Saisissez une adresse e-mail correct');
                        $correctforme='false';

                    }else
                    {
                        //echo 'meme';
                        $correctforme='true';
                        break;
                    }

               // echo'nom'.$kk; 
            }}}
            //echo 'validation'.$correctforme;
            if($correctforme == 'false'){
                $this->addFlash('incorrectemail', 'Saisissez une adresse e-mail correct');
                
            }
            else{
               // echo 'meme';
           

            
       // echo 'tst'.$request->get('role');
        $empexist = $dm->getRepository(Employe::class)->findBy(['idemp'=>$request->get('idemployer')]);
        //$empexist = $dm->getRepository(Employe::class)->findOneBy(['idemp'=>$request->get('idemployer')]);
        // $empexist = $dm->createQueryBuilder('\App\Document\Employe')
        // ->field('idemp')->equals(['idemp'=>$request->get('role')])
        // ->getQuery()
        // ->getSingleResult(); 
        if($empexist != null){
            echo 'exist';
        }else {echo 'notexiste';}
       
    //    if($empexist->getEmail()!=null){ 
          
    //        $this->addFlash('danger', 'Compte déjà existé');
    //    }else
    //     {
       
            
        foreach($empexist as $emp){
            $nom=explode(" ", $emp->getNom());
            $prefixnom=$nom[0];
           
            // echo uniqid($prefixnom);
            $password=substr(str_shuffle($permitted_chars), 0, 10);
           // echo $password;
            $emp->setEmail($emailemp);
            $emp->setDateDeNaissance($password);
        $emp->setPassword($passwordEncoder->encodePassword(
            $emp ,$password));
        }
         //echo $password;
        
        $dm->flush();
        $paramtre = $dm->getRepository(ParametrageEmail::class)->findAll();
        foreach($paramtre as $parm){
            $host=$parm->getHost();
            
            $mail=$parm->getEmail();
            $password=$parm->getPassword();
            $port=$parm->getPort();
            $user=$parm->getUsername();
        }
        $mailmodifier=str_replace("@","%40",$mail);

        //  $var='smtp://'.$mailmodifier.':'.$password.'@'.$host.':'.$port;
        $var='smtp://'.$mailmodifier.':'.$user.$password.'@'.$host.':'.$port;

    // $var='smtp://ahlemthameur0%40gmail.com:AH13406574@smtp.gmail.com:465';
        $loader = new FilesystemLoader('C:\wamp64\www\projet\templates');
$twig = new Environment($loader);
$transport = Transport::fromDsn($var);
$mailer = new Mailer($transport);
$email = (new TemplatedEmail())
          ->from('ahlemthameur0@gmail.com')
            ->to('thameuramel27@gmail.com')
            ->subject('Nouvelle Compte')
            ->htmlTemplate('emails/emailnewcompet.html.twig')
            ->context([
                'login'=>$emailemp,
               'password' =>$password,
               
               'url'=>$url
               
           ]);
            $renderer = new BodyRenderer($twig);
            $renderer->render($email);
    $mailer->send($email); 

    //     $email = (new TemplatedEmail())
    //       ->from('ahlemthameur0@gmail.com')
    //         ->to('thameuramel27@gmail.com')
    //      ->subject('Nouvelle Compte')
        
    //      ->htmlTemplate('emails/emailnewcompet.html.twig')
    //      ->context([
    //          'login'=>$email,
    //         'password' =>$password,
    //         'nom'=>$prefixnom
            
    //     ]);


    // $mailer->send($email); 
    //$this->addFlash('message','msg envoye');
    $this->addFlash('info', 'Votre compte cree avec success');
     $this->redirectToRoute('newaccount');
    // return $this->render('login/index.html.twig');

}} 
//}
else{
    $this->addFlash('empty', 'Saisissez une adresse e-mail');
}}
        
        return $this->render('newcompter/index.html.twig', [
            'controller_name' => 'NewcompterController','employe'=>$output
        ]);
    }
}
