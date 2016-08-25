<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;


/**
 * Classe Utils contient des fonctionnalité commune aux differents controller
 * Je l'isole ici => allège les controllers 
 * ces services sont réutilisables
 * 
 */
class Utils {

    protected $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    /**
     * getList reçoit un nom d'entité. Il va se charger de récuperer la liste des differents objet
     * retourne cette liste
     */
    public function getList($entite) {

        $repo = $this->em->getRepository("AppBundle\Entity\\" . $entite);
        return $repo->myFindAll();
    }

    /**
     *  myPersist recoit une action et le nom de l'entité
     * action == persit => perist
     * ection == remove=> remove
     * renvoie l'id du de l'objet concerné 
     */
    public function myPersist($action=null,$entite) {
        ($action=="persist") ? $this->em->persist($entite):( ($action=="remove")?$this->em->remove($entite):null);
        $this->em->flush();
       
        return $entite->getId();;
    }

}
