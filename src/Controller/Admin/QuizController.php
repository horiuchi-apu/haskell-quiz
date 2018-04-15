<?php

namespace App\Controller\Admin;

use App\Repository\QuizRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class QuizController
 * @package App\Controller\Admin
 * @Route("/admin/quiz")
 */
class QuizController extends Controller
{
    /**
     * @Route("/", name="admin_quiz_index")
     */
    public function index(Request $request, QuizRepository $repository)
    {
        $query = $repository->createQueryBuilder('quiz')->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('Admin/quiz/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
