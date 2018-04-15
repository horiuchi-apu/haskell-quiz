<?php

namespace App\Controller\Front;

use App\Entity\Answer;
use App\Entity\Quiz;
use App\Entity\Section;
use App\Form\Front\CreateAnswerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class QuizController
 * @package App\Controller\Front
 * @Security("has_role('ROLE_USER')")
 * @Route("/quiz")
 */
class QuizController extends Controller
{
    /**
     * @Route("/", name="quiz_index")
     */
    public function index()
    {
        return $this->render('Front/quiz/index.html.twig', []);
    }

    /**
     * @Route("/section/{slug}", name="quiz_section")
     */
    public function section(Section $section)
    {
        $form = $this->createForm(CreateAnswerType::class);

        return $this->render('Front/quiz/section.html.twig', [
            'section' => $section,
            'formObject' => $form,
        ]);
    }


    /**
     * @Route("/grading/{id}", name="quiz_grading")
     */
    public function grading($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository(Quiz::class)->find($id);

        if ($quiz) {
            $answer = new Answer();
            $form = $this->createForm(CreateAnswerType::class, $answer);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $user = $this->getUser();
                $answer->setUser($user);
                $answer->setQuiz($quiz);
                $answer->grading();

                $em->persist($answer);
                $em->flush();


                return new JsonResponse([
                    'quizId' => $quiz->getId(),
                    'message' => $answer->getMessage(),
                    'isRight' => $answer->isRight(),
                ]);
            }
        }

        return new JsonResponse([
            'quizId' => $quiz->getId(),
            'message' => 'エラーが発生しました。'
        ], 500);
    }


}