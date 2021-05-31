<?php

namespace App\Controller\Admin; 

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport ;
use Symfony\Component\Mailer\Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Document\ParametreGenerale; 
use Symfony\Component\Mailer\EventListener\MessageListener;
use Twig\Environment as TwigEnvironment;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\ParametrageEmail; 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class ParametrageadController extends AbstractController
{
     
    /**
     * * @IsGranted("ROLE_ADMIN")
     * @Route("/parametersmtp", name="parametersmtp")
     */
    public function index(MailerInterface $mailer,Request $request,DocumentManager $dm): Response
    {  
        dump($request);
       
      
            $count = $dm->createQueryBuilder('App\Document\ParametrageEmail')->count()->getQuery()->execute();
            if($count!=0){

                if($request->query -> count()>0){
                    
                    $paramtre =  $dm->getRepository(ParametrageEmail::class)->findAll();
                    // if($paramtre!= null){echo 'existe';}
                    // else {echo 'not existe';}
                    foreach($paramtre as $paramtres){ 

                    $paramtres->setEmail($request->query->get('email'));
                    $paramtres->setUsername($request->query->get('username'));
        $paramtres->setHost($request->query->get('host'));
        $paramtres->setPassword($request->query->get('password'));
        $paramtres->setPort($request->query->get('port'));
       
      
        $dm->flush();}
                }
                
                
               
            }
        else {
            $paramtre= new ParametrageEmail ();
            $paramtre->setEmail('');
            $paramtre->setHost('');
            $paramtre->setUsername('');
            $paramtre->setPassword('');
            $paramtre->setPort('');
            
            
        $dm->persist($paramtre);
           $dm->flush();
            
        }
         
//         //echo $toBechecked;
//         $var='smtp://ahlemthameur0%40gmail.com:AH13406574@smtp.gmail.com:465';
//         $loader = new FilesystemLoader('C:\wamp64\www\projet\templates');
// $twig = new Environment($loader);
//         //$twig = new TwigEnvironment($loader);
// //$messageListener = new MessageListener(new BodyRenderer($twig));
 
// // $eventDispatcher = new EventDispatcher();
// // $eventDispatcher->addSubscriber($messageListener);
//         $transport = Transport::fromDsn($var);
// $mailer = new Mailer($transport);
// $email = (new TemplatedEmail())
//           ->from('ahlemthameur0@gmail.com')
//             ->to('thameuramel27@gmail.com')
//             ->subject('Time for Symfony Mailer!')
//             ->htmlTemplate('emails/emailnewcompet.html.twig')
//             ->context([
//                 'login'=>'$email',
//                'password' =>'$password',
//                'nom'=>'$prefixnom'
               
//            ]);
//             $renderer = new BodyRenderer($twig);
//             $renderer->render($email);
    ///////////$mailer->send($email); 
        //echo $_ENV['emailuser'];

       
            $paramtre = $dm->getRepository(ParametrageEmail::class)->findAll();
            foreach($paramtre as $parm){
                $host=$parm->getHost();
                $username=$parm->getUsername();
                $mail=$parm->getEmail();
                $password=$parm->getPassword();
                $port=$parm->getPort();
                
            }
        
    
   
        return $this->render('Admin/parametragead/index.html.twig', [
            'controller_name' => 'ParametrageadController', 'host'=>$host,'email'=>$mail,'password'=>$password,'port'=>$port,'username'=>$username
        ]);
    }
      /**
     * @Route("/testserver", name="testserver")
     */
    public function testserveur(Request $request,DocumentManager $dm): Response{
        $paramtregeneral =  $dm->getRepository(ParametreGenerale::class)->findAll();
                   
        foreach($paramtregeneral as $paramtres){
            $url=$paramtres->getUrl();
            
        }
        if($request->request->count()>0){
            $emailtst= $request->request->get('email');
            $paramtre = $dm->getRepository(ParametrageEmail::class)->findAll();
            foreach($paramtre as $parm){
                $host=$parm->getHost();
                
                $mail=$parm->getEmail();
                $password=$parm->getPassword();
                $port=$parm->getPort();
                $user=$parm->getUsername();
                
            } 
            $mailmodifier=str_replace("@","%40",$mail);
            $var='smtp://'.$mailmodifier.':'.$user.$password.'@'.$host.':'.$port;
           
         //  $var='smtp://ahlemthameur0%40gmail.com:AH13406574@smtp.gmail.com:587?encryption=tls';
                 $loader = new FilesystemLoader('C:\wamp64\www\projet\templates');
                 $twig = new Environment($loader);
                 $transport = Transport::fromDsn($var);
                 $mailer = new Mailer($transport);
                $email = (new TemplatedEmail())
                ->from($mail)
                  ->to($emailtst)
               ->subject('Tester Connexion SMTP ')
              
               ->htmlTemplate('emails/emailtestserveur.html.twig')
               ->context([
                   
                'message'=>'Connexion valide, serveur configuré',
                'url'=>$url

                  
              ]);
      
              $renderer = new BodyRenderer($twig);
              $renderer->render($email);
          $mailer->send($email);
         
            $this->addFlash('message', 'consulter votre boîte e-mail');
                return $this->redirectToRoute('parametersmtp');
        }

    }
    
}
