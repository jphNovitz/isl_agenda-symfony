<?php

/**
 * Class pour la gestion/creation des messages flash
 * 
 * La méthode MyFlashes ne fait qu'appeler la methode flash() qui existe déjà
 * Dans mon code appeler cette methode est plus court et plus rapide que les diffet ->get
 * et puis le faire m'apprend
 * 
 */

namespace AppBundle\Utils;

use Symfony\Component\HttpFoundation\Request;

class MyFlashes {

    static function flash(Request $request, $type, $message) {
        $request->getSession()
                ->getFlashBag()
                ->add($type, $message);
    }

}
