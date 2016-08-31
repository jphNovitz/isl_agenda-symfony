<?php

/**
 * Isl Agenda Symfony / page EventController.php
 * Petit agenda d'évènement 
 * Juin-Septembre 2016
 * Novitz Jean-Philippe
 * @jeanphinov on Github
 * hello@jiphi.be 
 * 
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Form\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class EventController extends Controller {

    /**
     * @Route("/event/list/", name="event_list")
     */
    public function listAction() {

        $events = $this->get("utils")->getList("Event");
        return $this->render('public/event-list.html.twig', ['events' => $events]);
    }

    /**
     * @Route("/event/{id}", name="event_detail")
     * @ParamConverter("event", class="AppBundle:Event")
     */
    public function detailAction(Request $request, Event $event = null, $id) {

        try {
            if (empty($event)) {
                throw new Exception("Evènement introuvable");
            }
            return $this->render('public/event-detail.html.twig', ['event' => $event]);
        } catch (Exception $e) {
            $this->addFlash("warning", $e->getMessage());
            return $this->redirectToRoute("event_list");
        }
    }

    /**
     * @Route("/admin/event/", name="admin_event_list")
     */
    public function adminListAction() {
        $events = $this->get("utils")->getList("Event");
        return $this->render('admin/event-list.html.twig', ['events' => $events]);
    }

    /**
     * @Route("/admin/event/add/", name="event_add")
     */
    public function addAction(Request $request) {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $id = $this->get("Utils")->myPersist($event);
            $this->addFlash("success", "l'élement a bien été ajouté");

            return $this->redirectToRoute('event_detail', ['id' => $id]);
        }

        return $this->render('admin/event-form.html.twig', ['form' => $form->createView(), 'action' => 'ajout']);
    }

    /**
     * @Route("/admin/event/update/{id}")
     *  requirements={"id": "\d+"},
     *  defaults={"id": null},
     *  name="event_update")
     * @paramConverter("event", class="AppBundle:Event")
     */
    public function updateAction(Request $request, Event $event, $id) {

        try {
            if (empty($event)) {
                throw new Exception("élément introuvable");
            }

            /**
             * Creation du formulaire
             */
            $form = $this->createForm(EventType::class, $event); /* j'hydrate le formulaire avec l'objet $event */
            $form->handleRequest($request);
            /**
             * Je passe la requete au formulaire, il la traite:
             * - si le formulaire est "soumit" c'est qu'on arrive içi via un formulaire
             * - si le forulaire est valid alors on est bon
             */
            if ($form->isSubmitted() && $form->isValid()) {
                /**
                 * detecte si pour arriver ici l'utilisateur a  cliqué sur le boutton supprimer
                 * si oui je renvoie à la route qui s'occuppe de la suppression
                 */
                if ($form->get('supprimer')->isClicked()) {
                    return $this->redirectToRoute("admin_event_delete", ['id' => $id]);
                }

                $id = $this->get("Utils")->myPersist("persist",$event);
                $this->addFlash("success", "l'élement a bien été modifié");
            }
            return $this->render('admin/event-form.html.twig', ['form' => $form->createView(), 'action' => 'modification']);
        } catch (Exception $e) {
            $this->addFlash('warning', "Impossible de trouver l'évènement (" . $e->getMessage() . ")");
            return $this->redirectToRoute('admin_event_list');
        }
    }

    /**
     * @Route("/admin/event/delete/{id}", name="admin_event_delete")
     */
    public function deleteAction(Request $request, Event $event) {

        $form = $this->createFormBuilder($event)
                ->add('supprimer', SubmitType::class, ['label' => 'OUI Supprimer !'])
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $id = $this->get("Utils")->myPersist("remove",$event);
            $this->addFlash("success", "l'élement a bien été supprimé");
            return $this->redirectToRoute('event_list');
        }

        return $this->render('admin/form_delete_confirmation.html.twig', ['informations' => $event, 'form' => $form->createView()]);
    }


}
