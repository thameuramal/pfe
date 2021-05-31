<?php

namespace App\Controller\Emp;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Employe;
use App\Document\Societes; 
class ConsulterProfilEmpController extends AbstractController
{ 
    /**
     * @Route("/userprofile", name="userprofile")
     */
    public function index(DocumentManager $dm ,Request $request): Response
    {
         
        
        $user = $this->getUser();
        
       // $employes = $dm->getRepository(Employe::class)->findBy(['idemp' =>$user->getIdemp()]);
      //  echo 'user'.$user->getIdemp();
        //foreach($employes as $emp){
            $email=$user->getEmail();
            $nom=$user->getNom(); 
            
            $matricule=$user->getMatricule();
            $image=$user->getPhoto();
            $soc = $dm->getRepository(Societes::class)->findOneBy(['numero' =>$user->getSociete()]);
            
            $societe=$soc->getNom();
            //$passwordad=$employer->getPassword();
            //echo 'pss'.$passwordad;
      //  }
        dump($request);
        if($request->request-> count()>0){
            
            $file = $request->files->get('photo'); 
            if($file != null){
            $fileName = md5(uniqid()).'.'.$file->guessExtension(); 
            try {
                $file->move(
                    $this->getParameter('photo_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
           // echo'entre ';
            $employes = $dm->getRepository(Employe::class)->findBy(['idemp' =>$user->getIdemp()]);
foreach($employes as $employer){
    $employer->setNom($request->request->get('nom'));
    $employer->setMatricule ($request->request->get('matricule'));
    $employer->setEmail($request->request->get('email'));
    $employer->setPhoto($fileName);
    $dm->flush();}
    return $this->redirectToRoute('userprofile',['nom'=>$employer->getNom(),'matricule'=>$employer->getMatricule(),'email'=>$employer->getNom(),'image'=>$employer->getPhoto(),'societe'=>$employer->getSociete()]);

}
    else{
        $employes = $dm->getRepository(Employe::class)->findBy(['idemp' =>$user->getIdemp()]);
        foreach($employes as $employer){ 
            $employer->setNom($request->request->get('nom'));
            $employer->setMatricule ($request->request->get('matricule'));
            $employer->setEmail($request->request->get('email'));
            
            $dm->flush();
    }
    return $this->redirectToRoute('userprofile',['nom'=>$employer->getNom(),'matricule'=>$employer->getMatricule(),'email'=>$employer->getNom(),'image'=>$employer->getPhoto(),'societe'=>$employer->getSociete()]);
}
        }
       
        // dump($request);
        //    $file = $request->files->get('photo'); 
        // $fileName = md5(uniqid()).'.'.$file->guessExtension(); 
        // try {
        //     $file->move(
        //         $this->getParameter('photo_directory'),
        //         $fileName
        //     );
        // } catch (FileException $e) {
        //     // ... handle exception if something happens during file upload
        // }
        // $employe = new Employe();
        // $employe ->setNom('tt');
        // $employe ->setSociete('hhh');
        // $employe ->setMatricule('hhh');
        // $employe ->setIdemp('lll');
      
        
        // $employe ->setPhoto($fileName); 
        // $dm->persist($employe);
        // $dm->flush();

        return $this->render('Emp/consulter_profil_emp/index.html.twig', [
            'controller_name' => 'ConsulterProfilEmpController','nom'=>$nom, 'email'=>$email,'matricule'=>$matricule,'image'=>$image, 'societe'=>$societe
        ]);
    }
}
