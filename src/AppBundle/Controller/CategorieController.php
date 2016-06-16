<?php

namespace AppBundle\Controller;

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
    public function detailAction()
    {


        return $this->render();
    }
    
    /**
     * @Route("/admin/categorie/add/", name="categorie_add")
     */
    public function addAction()
    {


        return $this->render();
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
