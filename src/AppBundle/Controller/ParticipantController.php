<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Participant;
use AppBundle\Form\ParticipantType;
use AppBundle\Utils\MyFlashes;
use AppBundle\Utils\MyDelete;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class ParticipantController extends Controller {

    /**
     * @Route("/participant/list/", name="participant_list")
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle\Entity\Participant');
        // $participants=$repo->findAll(); findAll() remplacé par myFindAll()
        $participants = $repo->myFindAll();

        return $this->render('public/participant-list.html.twig', ['participants' => $participants]);
    }

    /**
     * @Route("/participant/{id}", name="participant_detail")
     */
    public function detailAction($id) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle\Entity\Participant');
        $participant = $repo->find($id);


        return $this->render('public/participant-detail.html.twig', ['participant' => $participant]);
    }

    /**
     * @Route("/admin/participant/list/", name="admin_participant_list")
     */
    public function adminListAction() {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle\Entity\Participant');
        $participants = $repo->myFindAll();

        return $this->render('admin/participant-list.html.twig', ['participants' => $participants]);
    }

    /**
     * @Route("/admin/participant/add/", name="participant_add")
     */
    public function addAction(Request $request) {

        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->add('ajout', SubmitType::class, ['label' => 'Ajouter !', 'attr' => array('class' => 'btn btn-default')]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participant);
            $em->flush();

            MyFlashes::flash($request, 'success', 'l\'élement a bien été ajouté');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('admin/participant-form.html.twig', ['form' => $form->createView(), 'action' => 'ajout']);
    }

    /**
     * @Route("/admin/participant/update/{id}", 
     *  requirements={"id": "\d+"}, 
     *  defaults={"id": null}, 
     *  name="participant_update")
     */
    public function updateAction(Request $request, $id) {

        try {
            if (is_null($id)) {
                throw new Exception("id ne peut etre null");
                // je leve une exception si $id=null
            }
            if ($id == 0) {
                throw new Exception("zero n'est pas un id valide");
                // je leve une exception si $id=0
            }
            
            /* recuperation du participant correspondant à id */
            $participant = new Participant();
            $manager = $this->getDoctrine()->getManager();
            $repo = $manager->getRepository('AppBundle\Entity\Participant');
            $participant = $repo->find($id);

            if (empty($participant)) {
                throw new Exception("L'élément n'existe pas");
                // je lève une exception si je ne trouve pas l'enregistrement
            }

            /*
             * creation du formulaire rempli avec les info récupérée 
             * ajout de boutons ajout et supprimer (ajout parce que dans la vue le meme formulaire sert d'ajout et d'update
             */
            $form = $this->createForm(ParticipantType::class, $participant);
            $form->add('ajout', SubmitType::class, ['label' => 'Modifier !', 'attr' => array('class' => 'btn btn-default')])
                    ->add('supprimer', SubmitType::class, ['label' => 'Supprimer !', 'attr' => array('class' => 'btn btn-danger')]);

            /* je verifie que le formulaire j'arrive ici via submission du formulaire et que celui ci est valide */
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('supprimer')->isClicked()) {
                    return $this->redirectToRoute('participant_delete', ['id' => $id]);
                }
                $manager->persist($participant);
                $manager->flush();

                MyFlashes::flash($request, 'success', 'l\'élement a bien été modifié');

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


            MyFlashes::flash($request, 'warning', $e->getMessage());
            return $this->redirectToRoute('admin_participant_list');
        }
    }

    /**
     * @Route("/admin/participant/delete/{id}", name="participant_delete")
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $participant = $em->getRepository('AppBundle\Entity\Participant')->find($id);

        $form = $this->createFormBuilder($participant)
                ->add('supprimer', SubmitType::class, ['label' => 'OUI Supprimer !'])
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($participant);
            $em->flush();
            MyFlashes::flash($request, "success", "l'élement a bien été supprimé");
            return $this->redirectToRoute('admin_participant_list');
        }

        return $this->render('admin/form_delete_confirmation.html.twig',
        ['informations' => $participant, 'form' =>$form->createView()]);
    }

}
