<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Document\Employe;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Document\Societes;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport ;
use Symfony\Component\Mailer\Mailer;
use App\Document\ParametreGenerale; 

use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Document\ParametrageEmail;
use App\Document\Retard; 
use App\Document\ChektTimeIn;
use App\Document\ChektTimeOut;
use App\Document\AlerteReatard;
class LoginController extends AbstractController
{




    /**
     * @Route("/login", name="login")
     */
    public function index(DocumentManager $dm ,Request $request): Response
    {
        dump($request);
        echo 'phone'.$request->query->get('phone');
        echo 'dateerrrr'.$request->query->get('to');
        // if('13:00:00'>'13:30:40'){echo 'datepertite';}else{ echo 'dategrand';}
        // $currDate = "2020-04-18";
        // $changeDate = date("d/m/Y", strtotime($currDate));
        // echo "Changed date format is: ". $changeDate. " (MM-DD-YYYY)";
        // $societe =  $dm->getRepository(Societes::class)->findAll();
        // foreach($societe as $societes){
            
        //     $employes = $dm->getRepository(Employe::class)->findBy(['societe' =>$societes->getNumero()]);
        //     foreach($employes as $employer){
                
               
        //                     $nbralete = $dm->getRepository(AlerteReatard::class)->findBy(['idemp' =>$employer->getIdemp()]);

        //                     // $nbralete = $dm->createQueryBuilder('\App\Document\AlerteReatard')
        //                     // ->field('idemp')->equals($employer->getIdemp())
        //                     //  ->getQuery()
        //                     // ->getSingleResult();
        //                     foreach ($nbralete as $nbraletes){
        //                  echo 'id'.$nbraletes->getIdemp();
        //                  echo 'alert'.$nbraletes->getNbalerte();
        //                  echo 'date'.$nbraletes->getDate();}
        //                 }}


        // $heuertravail = $dm->createQueryBuilder('\App\Document\Employe')
        // ->field('idemp')->equals('2')
        // ->getQuery() 
        // ->getSingleResult();

        // if($heuertravail == null){echo 'not exsite';}
        // else { echo 'existe';}
        $month = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
        echo 'month*****'.$month[01];
        $monthNum  = 1;
        $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
        echo'date'.$monthName;
        $jour =  explode('/', '1/2021');
        echo $jour[1];
       
 setlocale(LC_ALL, 'french'); 
$monthName  = strftime("%B",mktime(0, 0, 0, $monthNum,10));
echo  'tst'.' '.' '.$monthName ;
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
    /**
     * @Route("/testlogin", name="logine")
     */
    public function tstlogin(){
        return $this->render('login/login.html.twig');
    }
     /**
     * @Route("/testlogine", name="logine")
     */
    public function tstlogine(){
        return $this->render('login/testtwig.html.twig');
    }

     /**
     * @Route("/testrole", name="testrole")
     */
    public function tstrole(Request $request, DocumentManager $dm ){
        
//  <form class="form-login" action="{{path('testrole')}}" methode="post">
        dump($request);
    //     $employe = new Employe();
    //     $employe ->setNom('Ali');
        
    //     $employe ->setMatricule('0000');
       
    //     $employe ->setRole('ADMIN');
    //     $dm->persist($employe);
    //    $dm->flush();
       $matricule= $request->query->get('matricule'); 
       $usere =  $dm->getRepository(Employe::class)->findBy(['matricule' => $matricule]); 
        foreach($usere as $users)
        {
            
         $role=$users->getRole();
         if($role == 'ADMIN')
         {
             return $this->redirectToRoute('statistique_ad');
         }
         else if($role == 'employe'){
            // $this->container->get('session')->set('user', $user);
            return $this->redirectToRoute('consulterhistorique_emp',array('id'=>$users->getIdemp()));
         }
         else if($role == 'RH') 
           { return $this->redirectToRoute('historique_rh',['id'=>$users->getIdemp(),'numerosociete'=>$users->getSociete()]);}
            
            
        } 
      
        
        return $this->render('login/login.html.twig');
       
    }
     /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
   
     /**
     * @Route("/", name="app_login")
     */
    public function login(Request $request, DocumentManager $dm ,AuthenticationUtils $authenticationUtils,UserPasswordEncoderInterface $passwordEncoder ){
         
        //add admin 
        $userad = $dm->createQueryBuilder('\App\Document\Employe')
        ->field('roles')->equals('ROLE_ADMIN')
        
        ->getQuery()
        ->getSingleResult();
        if($userad == null){
                $employe = new Employe(); 
        $employe ->setNom('ADMIN  ADMIN ');
     
        $roles[] = 'ROLE_ADMIN';
        $employe ->setRoles($roles); 
        $password='ml8$ldm@t'; 
        $employe->setEmail('admin@sharing.com.tn');
            $employe->setDateDeNaissance($password);
        $employe->setPassword($passwordEncoder->encodePassword(
            $employe,$password));
        $dm->persist($employe);
        $dm->flush();
           // echo'existe';
        }
        
    //     $employe = new Employe(); 
    //     $employe ->setNom('ADMIN  ADMIN ');
    //    // $employe ->setIdemp('00');
    //     //$employe ->setMatricule('0000');
    //     //$employe ->setMatricule('0000');
    //     $roles[] = 'ROLE_ADMIN';
    //     $employe ->setRoles($roles); 
    //     $password='ml8$ldm@t';
    //     $employe->setEmail('admin@sharing.com.tn');
    //         $employe->setDateDeNaissance($password);
    //     $employe->setPassword($passwordEncoder->encodePassword(
    //         $employe,$password));
    //     $dm->persist($employe);
    //     $dm->flush();
    // $employes = $dm->getRepository(Employe::class)->findBy(['idemp' =>'00']);
    // foreach($employes as $employer){
    //     //echo 'tst'.$employer->getRoles();
    //     $tt=$employer->getRoles();
    //     foreach($tt as $t){
    //           echo 'tst'.$t;
    //     }
    // }
 
        if ($this->getUser()) {
          
        if ($this->isGranted('ROLE_ADMIN')){return $this->redirectToRoute('statistique_ad');}
        else if ($this->isGranted('EMPLOYER')){
          return $this->redirectToRoute('consulter_profil_emp'); }
         
        else if ($this->isGranted('RH'))
        {  return $this->redirectToRoute('historique_rh');}}
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
               
        return $this->render('login/connexion.html.twig',['last_username' => $lastUsername, 'error' => $error]);
               
            }
           
    /**
     * @Route("/motdepasseoublie", name="forgetpassword")
     */
    public function forgetpassword(Request $request,MailerInterface $mailer,TokenGeneratorInterface $tokenGenerator ,UserPasswordEncoderInterface $passwordEncoder,DocumentManager $dm ){
         dump($request); 
         $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
         if($request->request->count()>0){
             $email= $request->request->get('email');
             $matricule=$request->request->get('matricule');
            $employe =  $dm->getRepository(Employe::class)->findOneBy(array('email' =>$email,'matricule'=>$matricule));
             // Si l'utilisateur n'existe pas
            if ($employe === null) {
                // On envoie une alerte disant que l'adresse e-mail est inconnue
                $this->addFlash('danger', 'Cette adresse e-mail est inconnue');
                
                // On retourne sur la page de connexion
                return $this->redirectToRoute('app_login');
            }
             // Si l'utilisateur existe on  génère un token
             $token = $tokenGenerator->generateToken();

             // On essaie d'écrire le token en base de données
             try{
                 $employe->setResetToken($token);
                 
                 $dm->persist($employe);
                 $dm->flush();
             } catch (\Exception $e) {
                 $this->addFlash('warning', $e->getMessage());
                 return $this->redirectToRoute('app_login');
             }
             $user = $dm->getRepository(Employe::class)->findOneBy(['reset_token' => $token]);

        // Si l'utilisateur n'existe pas
        if ($user === null) {
            // On affiche une erreur
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('app_login');
        }
        $user->setResetToken(null);
        $nom=explode(" ", $user->getNom());
            $prefixnom=$nom[0];
          
            // echo uniqid($prefixnom);
            $password= $prefixnom.substr(str_shuffle($permitted_chars), 0, 5);
            echo $password;
            $user->setPassword($passwordEncoder->encodePassword(
                $user ,$password));
                $user->setDateDeNaissance($password);
                $dm->flush();
                //ennvoyer email
                $paramtregeneral =  $dm->getRepository(ParametreGenerale::class)->findAll();
            
            foreach($paramtregeneral as $paramtregenerals){ 
                $url=$paramtregenerals->getUrl();
            } 
                $paramtre = $dm->getRepository(ParametrageEmail::class)->findAll();
                foreach($paramtre as $parm){
                    $host=$parm->getHost();
                    
                    $mail=$parm->getEmail();
                    $password=$parm->getPassword();
                    $port=$parm->getPort();
                    
                }
                $mailmodifier=str_replace("@","%40",$mail);
                 $var='smtp://'.$mailmodifier.':'.$password.'@'.$host.':'.$port;
                 $loader = new FilesystemLoader('C:\wamp64\www\projet\templates');
                 $twig = new Environment($loader);
                 $transport = Transport::fromDsn($var);
                 $mailer = new Mailer($transport);
                $email = (new TemplatedEmail())
                ->from('ahlemthameur0@gmail.com')
                  ->to('thameuramel27@gmail.com')
               ->subject('Nouvelle mot de passe')
              
               ->htmlTemplate('emails/emailforgetpassword.html.twig')
               ->context([
                   
                  'password' =>$password,
                  'nom'=>$prefixnom
                  
              ]);
      
              $renderer = new BodyRenderer($twig);
              $renderer->render($email);
          $mailer->send($email);
                $this->addFlash('message', 'Mot de passe mis à jour , consulter votre boîte e-mail');
                return $this->redirectToRoute('app_login');
                 
            } 
            return $this->redirectToRoute('app_login');
    }
            
}
