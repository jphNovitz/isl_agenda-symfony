<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Event;

class EventController extends Controller
{
    /**
     * @Route("/event/list/", name="event_list")
     */
    public function listAction()
    {
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository('AppBundle\Entity\Event');
        $events=$repo->findAll();

        return $this->render('public/event-list.html.twig',['events'=>$events]);
    }
    
    /**
     * @Route("/event/{id}", name="event_detail")
     */
    public function detailAction()
    {


        return $this->render();
    }
    
    /**
     * @Route("/admin/event/add/", name="event_add")
     */
    public function addAction()
    {


        return $this->render();
    }
    
    /**
     * @Route("/admin/event/update/", name="event_update")
     */
    public function updateAction()
    {


        return $this->render();
    }
    
    /**
     * @Route("/admin/event/delete/", name="event_delete")
     */
    public function deleteAction()
    {


        return $this->render();
    }
    
    
}
