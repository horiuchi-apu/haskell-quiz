<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class UserController
 * @package App\Controller\Admin
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="admin_user_index")
     */
    public function index(Request $request, UserRepository $repository)
    {
        $query = $repository->createQueryBuilder('user')->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('Admin/user/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
