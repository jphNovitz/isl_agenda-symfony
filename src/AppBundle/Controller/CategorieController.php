<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categorie;
use AppBundle\Form\CategorieType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends Controller {

    /**
     * @Route("/categorie/list/", name="categorie_list")
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle\Entity\Categorie');
        $categories = $repo->findAll();

        return $this->render('public/categorie-list.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/categorie/{id}", name="categorie_detail")
     */
    public function detailAction($id) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle\Entity\Categorie');
        $categorie = $repo->find($id);


        return $this->render('public/categorie-detail.html.twig', ['categorie' => $categorie]);
    }

    /**
     * @Route("/admin/categorie/", name="categorie_admin_list")
     */
    public function adminListAction() {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle\Entity\Categorie');
        $categories = $repo->findAll();

        return $this->render('admin/categorie-list.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/admin/categorie/add/", name="categorie_add")
     */
    public function addAction(Request $request) {
        $categorie = new Categorie();

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->add('ajout', SubmitType::class, ['label' => 'Envoi !',]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            $this->flash($request, 'success', 'l\'élement a bien été ajouté');

            return $this->redirectToRoute('categorie_list');
        }

        return $this->render('admin/categorie_add.html.twig', ['form' => $form->createView(), 'action' => 'ajout']);
    }

    /**
     * @Route("/admin/categorie/update/{id}", name="categorie_update")
     * @Route("/admin/categorie/update/", defaults={"id":0}, name="categorie_update_noid")
     */
    public function updateAction(Request $request, $id) {
        if ($id == 0) {
            $this->flash($request, 'avertissement', 'Cet id n\'esiste pas');
            return $this->redirectToRoute('categorie_list');
        }

        $categorie = new Categorie();
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle\Entity\Categorie');
        $categorie = $repo->find($id);

        if ($categorie == null) {
            $this->flash($request, 'avertissement', 'Categorie inconnue !');
            return $this->redirectToRoute('categorie_add');
        }


        $form = $this->createForm(CategorieType::class, $categorie);
        $form->add('ajout', SubmitType::class, ['label' => 'Envoi !',])
                ->add('supprimer', SubmitType::class, ['label' => 'Supprimer !',]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('supprimer')->isClicked()) {
                return $this->redirectToRoute('categorie_delete', ['id' => $id]);
            }

            $em->persist($categorie);
            $em->flush();

            $this->flash($request, 'success', 'l\'élement a bien été modifié');
            return $this->redirectToRoute('categorie_list');
        }


        return $this->render('admin/categorie_add.html.twig', ['form' => $form->createView(), 'action' => 'modification']);
    }

    /**
     * @Route("/admin/categorie/delete/{id}", name="categorie_delete")
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository('AppBundle\Entity\Categorie')->find($id);

        $form = $this->createFormBuilder($categorie)
                ->add('supprimer', SubmitType::class, ['label' => 'OUI Supprimer !'])
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($categorie);
            $em->flush();
            $this->flash($request, 'success', 'l\'élement a bien été supprimé');
            return $this->redirectToRoute('categorie_list');
        }

        return $this->render('admin/form_delete_confirmation.html.twig',
        ['informations' => $categorie, 'form' =>$form->createView()]);
    }

    /*     * *
     *  Methodes pour soulager les controllers
     * * */

    public function flash(Request $request, $type, $message) {
        $request->getSession()
                ->getFlashBag()
                ->add($type, $message);
    }

}
