<?php

namespace App\Controller\Admin;

use App\Repository\AnswerRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AnswerController
 * @package App\Controller\Admin
 * @Route("/admin/answer")
 */
class AnswerController extends Controller
{
    /**
     * @Route("/", name="admin_answer_index")
     */
    public function index(Request $request, AnswerRepository $repository)
    {
        $query = $repository->createQueryBuilder('answer')->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('Admin/answer/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
