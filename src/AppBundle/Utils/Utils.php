<?php

namespace AppBundle\Utils;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\Container;

/**
 * 
 * 
 */
class Utils {

    protected $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function getList($entite) {

        $repo = $this->em->getRepository("AppBundle\Entity\\".$entite);
        return $repo->myFindAll();
    }

}
