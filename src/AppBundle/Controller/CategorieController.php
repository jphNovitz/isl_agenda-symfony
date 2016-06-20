<?php

namespace AppBundle\Controller;

use AppBundle\Form\CategorieType;
use Proxies\__CG__\AppBundle\Entity\Categorie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends Controller
{
    /**
     * @Route("/categorie/list/", name="categorie_list")
     */
    public function listAction()
    {
        $em=  $this->getDoctrine()->getManager();
        $repo=$em->getRepository('AppBundle\Entity\Categorie');
        $categories=$repo->findAll();
        
        return $this->render('public/categorie-list.html.twig',['categories'=>$categories]);
    }
    
    /**
     * @Route("/categorie/{id}", name="categorie_detail")
     */
    public function detailAction($id)
    {
        $em=  $this->getDoctrine()->getManager();
        $repo=$em->getRepository('AppBundle\Entity\Categorie');
        $categorie=$repo->find($id);


        return $this->render('public/categorie-detail.html.twig',['categorie'=>$categorie]);
    }
    
    /**
     * @Route("/admin/categorie/add/", name="categorie_add")
     */
    public function addAction(Request $request)
    {
        $categorie=new Categorie();
        $form=$this->createForm(CategorieType::class,$categorie);
        
        $form->add('ajout', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class,
                ['label' => 'Ajout',]);
        
        $form->handleRequest($request);
        
        
        if($form->isSubmitted() && $form->isValid()){
           
           
            $em=$this->getDoctrine()->getManager();
           
            $em->persist($categorie);
            $em->flush();
            
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'l\'élement a bien été ajouté');
                    
           return $this->redirectToRoute('categorie_list'); 
        }
        
        return $this->render('admin/categorie_add.html.twig',['form'=>$form->createView()]);


        
    }
    
    /**
     * @Route("/admin/categorie/update/", name="categorie_update")
     */
    public function updateAction()
    {


        return $this->render();
    }
    
    /**
     * @Route("/admin/categorie/delete/", name="categorie_delete")
     */
    public function deleteAction()
    {


        return $this->render();
    }
    
    
}
