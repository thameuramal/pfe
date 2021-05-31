<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Document\Employe;
use App\Document\Societes;
use Symfony\Component\Form\Forms;
// use Doctrine\ODM\MongoDB\DocumentManager as DocumentManager;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class GereemployAdController extends AbstractController

{
     
    /**
     * * @IsGranted("ROLE_ADMIN")
     * @Route("/gereemploy/ad", name="gereemploy_ad")
     */
    public function index(DocumentManager $dm ): Response
    {
        $employe =  $dm->getRepository(Employe::class)->findAll();
        
        return $this->render('Admin/gereemploy_ad/index.html.twig', [
            'controller_name' => 'GereemployAdController', 'employe'=>$employe
        ]);
    }
    /**
     * @Route("/createemploye", name="create_employe")
     */
    public function createemploye(Request $request, DocumentManager $dm )
    {

        $societes=new Societes();

        //pour recupere un attribut selon nom
        // $usere =  $dm->getRepository(Societes::class)->findBy(['nom' => 'IBS GROUPE']);
        // foreach($usere as $userr)
        // {
             
        //  $societes->setId($userr->getId());
            
        //      echo $societes->getId();
        // } 

        $user =  $dm->getRepository(Societes::class)->findAll();
    //    foreach($user as $userr)
    //    {
           
    //        $societes->setNom($userr->getNom());
           
    //         echo $societes->getNom();
    //    } 
       dump($user);
       $employe=new Employe();
        dump($request); 
        // if($request->query->count()>0){
            
        //   $dd=$request->query->get('naiss');
          
        //  $employe->setDateDeNaissance($dd);
        //  $employe->setNom($request->query->get('name'));
        //  $employe->setPrenom($request->query->get('prenom'));
        //  $employe->setSociete($request->query->get('societe'));
        //  $employe->setNumeroDeTelephone($request->query->get('Numero'));
        //  $employe->setMatricule($request->query->get('Matricule'));
        //  $employe->setEmail($request->query->get('Email'));
        //  $employe->setMotDePasse($request->query->get('motdepasse'));
             
          
         
        //   $dm->persist($employe);
        //   $dm->flush();
          
                
        //}
        
       
         $form =$this->createFormBuilder($employe)

           ->add('nom',TextType::class)
           ->add('prenom')
           ->add('dateDeNaissance',dateType::class , [
              'widget' => 'single_text'
              
          ])
          ->add('numeroDeTelephone')
           ->add('matricule')
           ->add('societe', ChoiceType:: class , [
               'choices'=>$this->getchioce($user)
           ])
           ->add('email')
           ->add('motDePasse',PasswordType::class)
           
            ->getForm();
           $form->handleRequest($request);
           dump($request);
           
           if($form->isSubmitted()&& $form->isValid()) {
            $dm->persist($employe);
            $dm->flush();
               return $this->redirectToRoute('create_employe');
           }
        //    $nomsoct=$employe->getSociete();
        //    $usere =  $dm->getRepository(Societes::class)->findBy(['nom' => $nomsoct]);
        //    foreach($usere as $userr)
        //    {
               
        //     $societes->setId($usere->getId());
               
        //          $societes->geId();
        //    } 
          

            
        return $this->render('Admin/gereemploy_ad/createemploye.html.twig',[
             'user'=>$user 
              , 'formEmploye'=>$form->createView() 
              
             
        ]);
    }
    public function getchioce($user){


        $output=[];
       foreach($user as $userr)
       {
        $output[$userr->getNom()]=$userr->getNumero();
          
       } 
       return $output;

    }
}
