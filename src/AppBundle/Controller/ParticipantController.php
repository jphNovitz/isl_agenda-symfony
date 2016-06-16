<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ParticipantController extends Controller
{
    /**
     * @Route("/participant/list/", name="participant_list")
     */
    public function listAction()
    {
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository('AppBundle\Entity\Participant');
        $participants=$repo->findAll();
        
        return $this->render('public/participant-list.html.twig',['participants'=>$participants]);
    }
    
    /**
     * @Route("/participant/{id}", name="participant_detail")
     */
    public function detailAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository('AppBundle\Entity\Participant');
        $participant=$repo->find($id);


        return $this->render('public/participant-detail.html.twig',['participant'=>$participant]);
    }
    
    /**
     * @Route("/admin/participant/add/", name="participant_add")
     */
    public function addAction()
    {


        return $this->render();
    }
    
    /**
     * @Route("/admin/participant/update/", name="participant_update")
     */
    public function updateAction()
    {


        return $this->render();
    }
    
    /**
     * @Route("/admin/participant/delete/", name="participant_delete")
     */
    public function deleteAction()
    {


        return $this->render();
    }
    
    
}
