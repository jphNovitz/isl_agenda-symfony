<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categorie;
use AppBundle\Form\CategorieType;
use AppBundle\Utils\MyFlashes;
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
        /*
         * $categories = $repo->findAll(); est remplacé par muFindAll qui ne séléctionne que les id et noms
         * plus cours et peut-être plus rapide
         */

        $categories = $repo->myFindAll();
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
        $categories = $repo->myFindAll();

        return $this->render('admin/categorie-list.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/admin/categorie/add/", name="categorie_add")
     */
    public function addAction(Request $request) {
        $categorie = new Categorie();

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->add('ajout', SubmitType::class, ['label' => 'Ajouter !', 'attr' => array('class' => 'btn btn-default')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            MyFlashes::flash($request, 'success', 'l\'élement a bien été ajouté');

            return $this->redirectToRoute('categorie_admin_list');
        }

        return $this->render('admin/categorie_add.html.twig', ['form' => $form->createView(), 'action' => 'ajout']);
    }

    /**
     * @Route("/admin/categorie/update/{id}", name="categorie_update")
     * @Route("/admin/categorie/update/", defaults={"id":0}, name="categorie_update_noid")
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

            $categorie = new Categorie();
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('AppBundle\Entity\Categorie');
            $categorie = $repo->find($id);

            if (empty($categorie)) {
                throw new Exception("L'élément n'existe pas");
                // je lève une exception si je ne trouve pas l'enregistrement
            }


            $form = $this->createForm(CategorieType::class, $categorie);
            $form->add('ajout', SubmitType::class, ['label' => 'Modifier !', 'attr' => array('class' => 'btn btn-default')])
                    ->add('supprimer', SubmitType::class, ['label' => 'Supprimer !', 'attr' => array('class' => 'btn btn-danger')]);

            /* je verifie que le formulaire j'arrive ici via submission du formulaire et que celui ci est valide */
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('supprimer')->isClicked()) {
                    return $this->redirectToRoute('categorie_delete', ['id' => $id]);
                }

                $em->persist($categorie);
                $em->flush();

                MyFlashes::flash($request, 'success', 'l\'élement a bien été modifié');
                return $this->redirectToRoute('categorie_list');
            }

            /*
             * si tout est ok je je persiste dans la BD et j'affiche la liste des participants 
             * sinon j'affiche le formulaire 
             */
            return $this->render('admin/categorie_add.html.twig', ['form' => $form->createView(), 'action' => 'modification']);
        }

        /*
         *  je recupere l'exception levée je crée un message flash et re redirrige vers la liste en indiquant pourquoi
         */ catch (Exception $e) {


            MyFlashes::flash($request, 'warning', $e->getMessage());
            return $this->redirectToRoute('admin_participant_list');
        }
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
            MyFlashes::flash($request, 'success', 'l\'élement a bien été supprimé');
            return $this->redirectToRoute('categorie_list');
        }

        return $this->render('admin/form_delete_confirmation.html.twig', ['informations' => $categorie, 'form' => $form->createView()]);
    }

}
