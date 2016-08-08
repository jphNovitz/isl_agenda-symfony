<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Participant;
use AppBundle\Form\ParticipantType;
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

            $this->flash($request, 'success', 'l\'élement a bien été ajouté');

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

        /* recuperation du participant correspondant à id */
        try {
            if (is_null($id))
                throw new Exception("id ne peut etre null");
            if ($id == 0)
                throw new Exception("zero n'est pas un id valide");

            $participant = new Participant();
            $manager = $this->getDoctrine()->getManager();
            $repo = $manager->getRepository('AppBundle\Entity\Participant');
            $participant = $repo->find($id);
            if (empty($participant))
                throw new Exception("L'élément n'existe pas");

            /*
             * creation du formulaire rempli avec les info récupérée 
             * ajout de boutons ajout et supprimer (ajout parce que dans la vue le meme formulaire sert d'ajout et d'update
             */
            $form = $this->createForm(ParticipantType::class, $participant);
            $form->add('ajout', SubmitType::class, ['label' => 'Modifier !', 'attr' => array('class' => 'btn btn-default')])
                    ->add('supprimer', SubmitType::class, ['label' => 'Supprimer !', 'attr' => array('class' => 'btn btn-danger')]);

            /* je verrifie que le formulaire j'arrive ici via submission du formulaire et que celui ci est valide */
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager->persist($participant);
                $manager->flush();
                $this->flash($request, 'success', 'l\'élement a bien été modifié');
                return $this->redirectToRoute('admin_participant_list');
            }
            /*
             * si tout est ok je je persiste dans la BD et j'affiche la liste des participants 
             * sinon j'affiche le formulaire 
             */
            return $this->render('admin/participant-form.html.twig', ['form' => $form->createView(), 'action' => 'modification']);
        } catch (Exception $e) {

            $this->flash($request, 'warning', $e->getMessage());
            return $this->redirectToRoute('admin_participant_list');
        }
    }

    /**
     * @Route("/admin/participant/delete/", name="participant_delete")
     */
    public function deleteAction() {


        return $this->render();
    }

    public function flash(Request $request, $type, $message) {
        $request->getSession()
                ->getFlashBag()
                ->add($type, $message);
    }

}
