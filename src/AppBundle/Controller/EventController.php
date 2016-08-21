<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Form\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

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
    public function addAction(Request $request) {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($event);
            $manager->flush();
            $id = $event->getId();
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
     */
    public function updateAction(Request $request, $id) {

        try {
            if (is_null($id)) {
                throw new Exception(" Verifiez l'id");
            }
            if ($id <= 0) {
                throw new Exception(" l'id fournie est impossible");
            }

            /**
             * Recuperation de l'objet evenement
             */
            $event = new Event();
            $manager = $this->getDoctrine()->getManager();
            $event = $manager->getRepository('AppBundle\Entity\Event')->find($id);
            if (empty($event)) {
                throw new Exception("l'élement est introuvable");
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

                $manager->persist($event);
                $manager->flush();

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

            $manager = $this->getDoctrine()->getManager();
            $manager->remove($event);
            $manager->flush();
            $this->addFlash("success", "l'élement a bien été supprimé");
            return $this->redirectToRoute('event_list');
            
        }
       
        return $this->render('admin/form_delete_confirmation.html.twig', ['informations' => $event, 'form' => $form->createView()]);
    }

}
