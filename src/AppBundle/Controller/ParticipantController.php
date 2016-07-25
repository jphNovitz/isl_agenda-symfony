<?php

namespace AppBundle\Controller;

use AppBundle\Form\ParticipantType;
use Proxies\__CG__\AppBundle\Entity\Participant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/admin/participant/add/", name="participant_add")
     */
    public function addAction(Request $request) {
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->add('ajout', SubmitType::class,['label' => 'Ajouter !', 'attr'  => array('class' => 'btn btn-default')]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->persist($participant);
            $em->flush();
            
            return $this->redirectToRoute('homepage');
        }

        return $this->render('admin/participant-form.html.twig', ['form' => $form->createView(), 'action' => 'ajout']);
    }

    /**
     * @Route("/admin/participant/update/", name="participant_update")
     */
    public function updateAction() {


        return $this->render();
    }

    /**
     * @Route("/admin/participant/delete/", name="participant_delete")
     */
    public function deleteAction() {


        return $this->render();
    }

}
