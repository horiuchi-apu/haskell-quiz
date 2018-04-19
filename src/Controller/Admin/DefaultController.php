<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 * @package App\Controller\Admin
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/admin")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin_index")
     */
    public function index()
    {
        return $this->render('Admin/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
