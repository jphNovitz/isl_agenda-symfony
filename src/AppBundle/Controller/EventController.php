<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    /**
     * @Route("/event/list/", name="event_list")
     */
    public function listAction()
    {


        return $this->render();
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
