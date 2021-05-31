<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use App\Document\ParametreGenerale; 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class ParametregeneralController extends AbstractController
{
    /**
     * * @IsGranted("ROLE_ADMIN")
     * @Route("/generalparameter", name="generalparameter")
     */
    public function index(Request $request,DocumentManager $dm): Response
    {  //dump($request);
        $domains[]='';
        $url= '';
        //$domains='';
       $senace= ''; 
         $heurematin='';
         $retardmatin='';
         $heureapresmidi='';
         $retardapresmidi='';
         $listedomaine='';
        $count = $dm->createQueryBuilder('App\Document\ParametreGenerale')->count()->getQuery()->execute();
       // echo'count'.$count;
        if($count!=0){
           
            if($request->request-> count()>0){
            $this->addFlash('warring', "vous n'êtes pas autorisé à ajouter de nouveaux paramètres, mais vous pouvez modifier ou supprimer les anciens paramètres.");
            }
          
            $paramtre = $dm->getRepository(ParametreGenerale::class)->findAll();
            foreach($paramtre as $parm){
              
               $url= $parm->getUrl();
               $domains=$parm->getDomaines ();
              $senace= $parm->getSeancetravail(); 
                $heurematin=$parm->getHeurematin();
                $retardmatin=$parm->getRetardmatin();
                $heureapresmidi=$parm->getHeureapresmidi();
                $retardapresmidi=$parm->getRetardapresmidi();
                
                 
            }
            $str = '';
            foreach($domains as $dom=>$k){
                //echo $k;
                if($k !=null){
                foreach($k as $kk){
                   $str=$str.$kk.'/' ;}
                }
                
            }
            //echo 'str'.$str;
            $listedomaine = substr($str, 0, -1);
       $existe=true;
        }
            else{
                //echo'entre'.$count;
                $existe=false;
                dump($request);
                if($request->request -> count()>0){

                $paramtres =  new ParametreGenerale;
                // if($paramtre!= null){echo 'existe';}
                // else {echo 'not existe';}
                    $domaines[] = $request->request->get('domaine'); 
                    $paramtres->setDomaines ($domaines); 
    $paramtres->setUrl($request->request->get('url')); 
    $paramtres->setSeancetravail($request->request->get('sens')); 
    if($request->request->get('sens')=='Double séance'){
        $paramtres->setHeurematin($request->request->get('matindouble'));
        $paramtres->setRetardmatin($request->request->get('retardmatindouble'));
    }
    else{$paramtres->setHeurematin($request->request->get('matinunique'));
        $paramtres->setRetardmatin($request->request->get('retardmatinunique'));}
    $paramtres->setHeureapresmidi($request->request->get('apresmidi'));
    $paramtres->setRetardapresmidi($request->request->get('retardapresmidi'));
    //echo'entre'.$request->query->get('url');
    $dm->persist($paramtres);
    $dm->flush();
    $this->addFlash('modifier', 'Les paramètres ont été sauvegardés');
    $existe=true;}
    $paramtre = $dm->getRepository(ParametreGenerale::class)->findAll();
    foreach($paramtre as $parm){
      
       $url= $parm->getUrl();
       $domains=$parm->getDomaines ();
      $senace= $parm->getSeancetravail(); 
        $heurematin=$parm->getHeurematin();
        $retardmatin=$parm->getRetardmatin();
        $heureapresmidi=$parm->getHeureapresmidi();
        $retardapresmidi=$parm->getRetardapresmidi();
        
         
    }
    $str = '';
    foreach($domains as $dom=>$k){
        //echo $k;
        if($k !=null){
        foreach($k as $kk){
           $str=$str.$kk.'/' ;}
        }
        
    
    //echo 'str'.$str;
    $listedomaine = substr($str, 0, -1);

}
         //  $this->addFlash('ajoute', 'Les paramètres ont été sauvegardés');
            }
            //echo 'string'.$listedomaine;
 
        
        return $this->render('Admin/parametregeneral/index.html.twig', [
            'controller_name' => 'ParametregeneralController','url'=>$url,'domaines'=>$listedomaine,'heurematin'=>$heurematin,'retardmatin'=>$retardmatin,
            'heureapresmidi'=>$heureapresmidi,'retardapresmidi'=>$retardapresmidi, 'senace'=>$senace,'existe'=>$existe
        ]);
    }
      /**
     * @Route("/updategeneralparameter", name="updategeneralparameter")
     */
    public function updategeneralparameter(Request $request,DocumentManager $dm): Response{
        // return $this->redirectToRoute('generalparameter'); 
        // 'url'=>$url,'domaines'=>$listedomaine,'heurematin'=>$heurematin,'retardmatin'=>$retardmatin,
        //     'heureapresmidi'=>$heureapresmidi,'retardapresmidi'=>$retardapresmidi, 'senace'=>$senace
        $paramtre = $dm->getRepository(ParametreGenerale::class)->findAll();
        foreach($paramtre as $parm){
          
           $url= $parm->getUrl();
           $domains=$parm->getDomaines ();
          $senace= $parm->getSeancetravail(); 
            $heurematin=$parm->getHeurematin();
            $retardmatin=$parm->getRetardmatin();
            $heureapresmidi=$parm->getHeureapresmidi();
            $retardapresmidi=$parm->getRetardapresmidi();}
            $str = '';
            foreach($domains as $dom=>$k){
                //echo $k;
                if($k !=null){
                foreach($k as $kk){
                   $str=$str.$kk.'/' ;}
                }
                
            }
            //echo 'str'.$str;
            $listedomaine = substr($str, 0, -1);
           // dump($request);
            


            
        return $this->render('Admin/parametregeneral/updateparamtre.html.twig', [
            'controller_name' => 'ParametregeneralController','url'=>$url,'domaines'=>$listedomaine,'heurematin'=>$heurematin,'retardmatin'=>$retardmatin,
            'heureapresmidi'=>$heureapresmidi,'retardapresmidi'=>$retardapresmidi, 'senace'=>$senace
        ]);
    }
    
     /**
     * @Route("/deletegeneralparameter", name="deletegeneralparameter")
     */
    public function deletegeneralparameter(Request $request,DocumentManager $dm): Response{
        $dm->getSchemaManager()->dropDocumentCollection(ParametreGenerale::class);
        return $this->redirectToRoute('generalparameter'); 
    }
  
     /**
     * @Route("/generalparameterupdate", name="generalparameterupdate")
     */
    public function generalparameterupdate(Request $request,DocumentManager $dm): Response{
        dump($request);
        if($request->request -> count()>0){
            $dm->getSchemaManager()->dropDocumentCollection(ParametreGenerale::class);
            $paramtres =  new ParametreGenerale;
            // if($paramtre!= null){echo 'existe';}
            // else {echo 'not existe';}
                $domaines[] = $request->request->get('domaine'); 
                $paramtres->setDomaines ($domaines);
$paramtres->setUrl($request->request->get('url'));
$paramtres->setSeancetravail($request->request->get('sens')); 
if($request->request->get('sens')=='Double séance'){
    $paramtres->setHeurematin($request->request->get('matindouble'));
    $paramtres->setRetardmatin($request->request->get('retardmatindouble'));
}
else{$paramtres->setHeurematin($request->request->get('matinunique'));
    $paramtres->setRetardmatin($request->request->get('retardmatinunique'));}
$paramtres->setHeureapresmidi($request->request->get('apresmidi'));
$paramtres->setRetardapresmidi($request->request->get('retardapresmidi'));
$dm->persist($paramtres);
$dm->flush();
$this->addFlash('modifier', 'Les paramètres ont été modifiés');
return $this->redirectToRoute('generalparameter'); 
    }
  
}}
