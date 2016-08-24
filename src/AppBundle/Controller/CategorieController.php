<?php

/**
 * Isl Agenda Symfony / page CategorieController.php
 * Petit agenda d'évènement 
 * Juin-Septembre 2016
 * Novitz Jean-Philippe
 * @jeanphinov on Github
 * hello@jiphi.be 
 * 
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Categorie;
use AppBundle\Form\CategorieType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CategorieController extends Controller {

    /**
     * @Route("/categorie/list/", name="categorie_list")
     */
    public function listAction() {

        //$categories = $this->getListCategories();
         $categories=  $this->get("utils")->getList("Categorie");

        return $this->render('public/categorie-list.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/categorie/{id}", name="categorie_detail")
     * @ParamConverter("categorie", class="AppBundle:Categorie")
     */
    public function detailAction(Request $request, Categorie $categorie = null) {

        try {
            if (empty($categorie)) {
                throw new Exception("L'élément n'existe pas");
            }
            return $this->render('public/categorie-detail.html.twig', ['categorie' => $categorie]);
        } catch (Exception $e) {
            $this->addFlash('warning', $e->getMessage());
            return $this->redirectToRoute('categorie_list');
        }
    }

    /**
     * @Route("/admin/categorie/", name="admin_categorie_list")
     */
    public function adminListAction() {

        $categories = $this->getListCategories();

        return $this->render('admin/categorie-list.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/admin/categorie/add/", name="admin_categorie_add")
     */
    public function addAction(Request $request) {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
// Fat Model Slim Controllers => je met le maximum concerant mon formumailre dans un fichier Type (il est fait pour cela)

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            $id = $categorie->getId();
            $this->addFlash("success", "l'élement a bien été ajouté");

            return $this->redirectToRoute('categorie_detail',['id'=>$id]);
        }

        return $this->render('admin/categorie_add.html.twig', ['form' => $form->createView(), 'action' => 'ajout']);
    }

  /**
     * @Route("/admin/categorie/update/{id}",
     *  requirements={"id": "\d+"},
     *  defaults={"id": null},
     *  name="admin_categorie_update")
     * @ParamConverter("categorie", class="AppBundle:Categorie")
     */
    public function updateAction($id, Request $request, Categorie $categorie = null) {

        try {

            if (empty($categorie)) {
                throw new Exception("L'élément n'existe pas");
                // je lève une exception si je ne trouve pas l'objet
            }

            $form = $this->createForm(CategorieType::class, $categorie);

            /* je verifie que le formulaire j'arrive ici via soumission du formulaire et que celui ci est valide */
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('supprimer')->isClicked()) {
                    return $this->redirectToRoute('admin_categorie_delete', ['id' => $id]);
                }
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($categorie);
                $manager->flush();
                $id=$categorie->getId();
                $this->addFlash("success", "l'élement a bien été modifié");

                return $this->redirectToRoute('categorie_detail', ['id'=>$id]);
            }

            /*
             * si tout est ok je je persiste dans la BD et j'affiche la categorie que je viens de modifier
             * sinon j'affiche le formulaire
             */
            return $this->render('admin/categorie_add.html.twig', ['form' => $form->createView(), 'action' => 'modification']);
        }
        /*
         *  je recupere l'exception levée je crée un message flash et re redirrige vers la liste en indiquant pourquoi
         */ catch (Exception $e) {


            $this->addFlash('warning', $e->getMessage());
            return $this->redirectToRoute('admin_categorie_list');
        }
    }

    /**
     * @Route("/admin/categorie/delete/{id}", name="admin_categorie_delete")
     */
    public function deleteAction(Request $request, Categorie $categorie) {
        /**
         * paramconverter recoit l'id et sait retrouver la categornie concernée
         */
        $form = $this->createFormBuilder($categorie)
                ->add('supprimer', SubmitType::class, ['label' => 'OUI Supprimer !'])
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorie);
            $em->flush();
            $this->addFlash("success", "l'élement a bien été supprimé");
            return $this->redirectToRoute('admin_categorie_list');
        }

        return $this->render('admin/form_delete_confirmation.html.twig', ['informations' => $categorie, 'form' => $form->createView()]);
    }

    /**
     *  Fonction getListCategorie()
     *  fonction qui sera utilisé pour créer les listes public et admin
     *  je la met ici je n'ai plus qu'à l'utiliser pour mes deux Action
     *  moins de code moins d'erreurs
     */
    public function getListCategories() {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle\Entity\Categorie');
        return $repo->myFindAll();
    }

}
