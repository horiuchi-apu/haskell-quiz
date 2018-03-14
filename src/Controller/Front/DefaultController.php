<?php

namespace App\Controller\Front;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 * @package App\Controller\Front
 * @Security("has_role('ROLE_USER')")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="front_index")
     */
    public function index()
    {
        return $this->render('Front/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
