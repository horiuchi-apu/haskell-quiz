<?php

namespace App\Controller\Admin;

use App\Entity\Quiz;
use App\Form\Admin\CreateQuizType;
use App\Form\Admin\EditQuizType;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
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

        $deleteForm = $this->createDeleteForm();
        return $this->render('Admin/quiz/index.html.twig', [
            'pagination' => $pagination,
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/create", name="admin_quiz_create")
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $quiz = new Quiz();
        $form = $this->createForm(CreateQuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($quiz);
            $em->flush();

            $this->addFlash('success', "問題を登録しました。");
            return $this->redirect($this->generateUrl('admin_quiz_index'));
        }

        return $this->render('Admin/quiz/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_quiz_edit")
     */
    public function edit(Quiz $quiz, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(EditQuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', "問題を編集しました。");
            return $this->redirect($this->generateUrl('admin_quiz_index'));
        }

        return $this->render('Admin/quiz/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_quiz_delete")
     */
    public function delete(Quiz $quiz, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createDeleteForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($quiz);
            $em->flush();

            $this->addFlash('success', "問題を削除しました。");
        }
        return $this->redirect($this->generateUrl('admin_quiz_index'));
    }


    public function createDeleteForm()
    {
        return $this->createFormBuilder()->getForm();
    }
}
