<?php

/**
 * Isl Agenda Symfony / page ParticipantController.php
 * Petit agenda d'évènement 
 * Juin-Septembre 2016
 * Novitz Jean-Philippe
 * @jeanphinov on Github
 * hello@jiphi.be 
 * 
 */

namespace AppBundle\Controller;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Participant;
use AppBundle\Entity\Event;
use AppBundle\Form\ParticipantType;
use AppBundle\Form\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class ParticipantController extends Controller {

    /**
     * @Route("/participant/list/", name="participant_list")
     */
    public function listAction() {

        $participants = $this->getListParticipant();

        return $this->render('public/participant-list.html.twig', ['participants' => $participants]);
    }

    /**
     * @Route("/participant/{id}", name="participant_detail")
     * @ParamConverter("participant", class="AppBundle:Participant")
     */
    public function detailAction(Request $request, Participant $participant = null) {

        try {
            if (empty($participant)) {
                throw new Exception("L'élément n'existe pas");
            }
            return $this->render('public/participant-detail.html.twig', ['participant' => $participant]);
        } catch (Exception $e) {
            $this->addFlash('warning', $e->getMessage());
            return $this->redirectToRoute('participant_list');
        }
    }

    /**
     * @Route("/admin/participant/list/", name="admin_participant_list")
     */
    public function adminListAction() {
        
        $participants = $this->getListParticipant();

        return $this->render('admin/participant-list.html.twig', ['participants' => $participants]);
    }

    /**
     * @Route("/admin/participant/add/", name="admin_participant_add")
     */
    public function addAction(Request $request) {

        /**
         * addAction => démarche
         *  - je crée le $form en utilisant un FormType
         *  - le $form gère la requête et teste
         *  - si j'arrive ici via le boutton submit d'un formulaire
         *  - si le formulaire est valide
         * 
         * Si oui et oui alors je persist le nouvel élément dans la BD
         * Si non c'est que je ne suis pas encore passé par un formulaire, je renvoie donc vers mon formulaire
         * 
         */
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        // Fat Model Slim Controllers => je met le maximum concerant mon formumailre dans un fichier Type (il est fait pour cela)

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participant);
            $em->flush();
            $id = $participant->getId();
            $this->addFlash("success", "l'élement a bien été ajouté");

            return $this->redirectToRoute('participant_detail', ['id' => $id]);
        }

        return $this->render('admin/participant-form.html.twig', ['form' => $form->createView(), 'action' => 'ajout']);
    }

    /**
     * @Route("/admin/participant/update/{id}",
     *  requirements={"id": "\d+"},
     *  defaults={"id": null},
     *  name="admin_participant_update")
     * @ParamConverter("participant", class="AppBundle:Participant")
     */
    public function updateAction(Request $request, Participant $participant = null, $id) {

        try {

            if (empty($participant)) {
                throw new Exception("L'élément n'existe pas");
                // je lève une exception si je ne trouve pas l'objet
            }

            /*
             * creation du formulaire rempli avec les info récupérée
             * ajout de boutons ajout et supprimer (ajout parce que dans la vue le meme formulaire sert d'ajout et d'update
             */

            $form = $this->createForm(ParticipantType::class, $participant);

            /* je verifie que le formulaire j'arrive ici via submission du formulaire et que celui ci est valide */
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('supprimer')->isClicked()) {
                    return $this->redirectToRoute('admin_participant_delete', ['id' => $id]);
                }
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($participant);
                $manager->flush();

                $this->addFlash("success", "l'élement a bien été modifié");

                return $this->redirectToRoute('admin_participant_list');
            }

            /*
             * si tout est ok je je persiste dans la BD et j'affiche la liste des participants
             * sinon j'affiche le formulaire
             */
            return $this->render('admin/participant-form.html.twig', ['form' => $form->createView(), 'action' => 'modification']);
        }
        /*
         *  je recupere l'exception levée je crée un message flash et re redirrige vers la liste en indiquant pourquoi
         */ catch (Exception $e) {


            $this->addFlash('warning', $e->getMessage());
            return $this->redirectToRoute('admin_participant_list');
        }
    }

    /**
     * @Route("/admin/participant/delete/{id}", name="admin_participant_delete")
     */
    public function deleteAction(Request $request, Participant $participant) {
        /**
         * paramconverter recoit l'id et sait retrouver le participant concerné
         */
        $form = $this->createFormBuilder($participant)
                ->add('supprimer', SubmitType::class, ['label' => 'OUI Supprimer !'])
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($participant);
            $em->flush();
            $this->addFlash("success", "l'élement a bien été supprimé");
            return $this->redirectToRoute('admin_participant_list');
        }

        return $this->render('admin/form_delete_confirmation.html.twig', ['informations' => $participant, 'form' => $form->createView()]);
    }
    
    /**
     *  Fonction getListParticipant()
     *  fonction qui sera utilisé pour créer les listes public et admin
     *  je la met ici je n'ai plus qu'à l'utiliser pour mes deux Action
     *  moins de code moins d'erreurs
     */

    public function getListParticipant() {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle\Entity\Participant');
        return $repo->myFindAll();
    }

}
