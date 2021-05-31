<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Societes; 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Document\Employe;
class IdentifierrhAdController extends AbstractController
{
    /**
     * * @IsGranted("ROLE_ADMIN")
     * @Route("/manage/users/profiles", name="manageusersprofiles")
     */ 
    public function index(DocumentManager $dm): Response
    {  $idtst='';
      
        $societes = $dm->getRepository(Societes::class)->findAll();
        foreach($societes as $societe){
        $employes = $dm->getRepository(Employe::class)->findBy(['societe' =>$societe->getNumero()]);
        $infoemp [] =  array(
            'nomsociete'=>$societe->getNom(),
            'idsociete'=>$societe->getNumero(),
            'employe'=>$employes

        ); 
        }
        return $this->render('Admin/identifierrh_ad/index.html.twig', [
            'controller_name' => 'IdentifierrhAdController','infoemp'=>$infoemp
        ]);
    } 
    /**
     * @Route("/Update/User/Profile/{{idemp}}/{{nom}}/{{societe}}/{{email}}/{{matricule}}", name="UpdateUserProfile")
     
     */
    public function modifierole(DocumentManager $dm,Request $request, $idemp,$nom,$societe,$email,$matricule): Response
    { 
        $roles[]='';
        $societers =  $dm->getRepository(Societes::class)->findOneBy(array('numero' => $societe));
        $societer = $dm->getRepository(Societes::class)->findAll();
        $numsoc=$societers->getNumero(); 
        $employe =  $dm->getRepository(Employe::class)->findOneBy(array('idemp' =>$idemp));
        $roles=$employe->getRoles();
        foreach($roles as $role=>$k){
                //echo 'dom'.$role;
                if($k!=null){
               // echo 'notvide';
                //echo'nom'.$k;
                $roleempl=$k;
              }
            }
          //echo'nom'.$roleempl;
        return $this->render('Admin/identifierrh_ad/modifierrh.html.twig', [
            'controller_name' => 'IdentifierrhAdController','idemp'=>$idemp ,'numsociete'=>$numsoc,'roleemployer'=>$roleempl,'nom'=>$nom,'societe'=>$societer,'email'=>$email,
            'matricule'=>$matricule
        ]);
    }
    // * @Route("/rolemodifier/{{societe}}/{{idemp}}", name="rolemodifier")

    /**
     * @Route("/rolemodifier/{{idemp}}", name="rolemodifier")
     
     */
    public function rolemodifier(DocumentManager $dm,Request $request,$idemp): Response
    { 
        dump($request);
        
         $employe =  $dm->getRepository(Employe::class)->findOneBy(array('idemp' =>$idemp));
       
       

            
            // 0006025280 
            $roles[] = $request->query->get('role');
         
        $employe ->setRoles($roles); 
        $employe->setNom($request->query->get('nom'));
        $employe->setEmail($request->query->get('email'));
        $employe->setMatricule($request->query->get('matricule'));
        $employe->setSociete($request->query->get('societe'));
            // $employe->setRole($request->query->get('role'));
            $dm->flush();
        

        
        return $this->redirectToRoute('manageusersprofiles');
    }
    /**
     * @Route("/profilemp", name="profilemp")
     
     */
    public function profilemp(DocumentManager $dm,Request $request): Response
    { 
         
        return $this->render('Emp/consulter_profil_emp/index.html.twig', [
            'controller_name' => 'ConsulterProfilEmpController','role'=>'admin'
        ]);
    }
      
      
}
