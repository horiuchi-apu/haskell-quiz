<?php

namespace App\Controller\Front;

use App\Entity\Answer;
use App\Entity\Quiz;
use App\Entity\Section;
use App\Form\Front\CreateAnswerType;
use App\Repository\SectionRepository;
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
    public function index(SectionRepository $repository)
    {
        $sections = $repository->findAll();
        return $this->render('Front/quiz/index.html.twig', [
            'sections' => $sections
        ]);
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

            if ($this->isContinuousPost($answer, $quiz)) {
                return new JsonResponse([
                    'quizId' => $quiz->getId(),
                    'message' => '連続投稿は禁止です',
                    'status' => 'danger',
                ]);
            }

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
                    'status' =>  $answer->isRight()? 'success': 'danger',
                ]);
            }
        }

        return new JsonResponse([
            'quizId' => $quiz->getId(),
            'message' => 'エラーが発生しました。',
            'status' => 'danger',
        ]);
    }


    // 連続投稿禁止のための暫定処置
    private function isContinuousPost(Answer $answer, $quiz)
    {
        $repo = $this->getDoctrine()->getRepository(Answer::class);
        $user = $this->getUser();

        return $repo->isContinuousPost($answer, $user, $quiz);
    }

}
