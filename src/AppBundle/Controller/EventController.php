<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Event;

class EventController extends Controller {

    /**
     * @Route("/event/list/", name="event_list")
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle\Entity\Event');
        //$events=$repo->findAll(); // find() remplacé par myFindAll()
        $events = $repo->myFindAll();

        return $this->render('public/event-list.html.twig', ['events' => $events]);
        //event est au pluriel car je renvoie un tableau de tous les events
    }

    /**
     * @Route("/event/{id}", name="event_detail")
     */
    public function detailAction($id) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle\Entity\Event');
        $event = $repo->find($id);


        return $this->render('public/event-detail.html.twig', ['event' => $event]);
        //event est au singulier car je renvoie le detail d'un event
    }

    /**
     * @Route("/admin/event/add/", name="event_add")
     */
    public function addAction() {


        return $this->render();
    }

    /**
     * @Route("/admin/event/update/", name="event_update")
     */
    public function updateAction() {


        return $this->render();
    }

    /**
     * @Route("/admin/event/delete/", name="event_delete")
     */
    public function deleteAction() {


        return $this->render();
    }

}
