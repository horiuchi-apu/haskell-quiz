<?php
/**
 * Created by PhpStorm.
 * User: Horiuchi
 * Date: 2018/04/21
 * Time: 21:21
 */

namespace App\Twig;

use App\Entity\Answer;
use App\Entity\Section;
use App\Entity\User;
use App\Repository\AnswerRepository;
use App\Repository\QuizRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MyPageExtension  extends AbstractExtension
{
    /**
     * @var QuizRepository
     */
    private $quizRepository;

    public function __construct(QuizRepository $quizRepository)
    {
        $this->quizRepository = $quizRepository;
    }

    public function getFilters()
    {
        return [
//            new TwigFilter('price', [$this, 'priceFilter']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_user_answered_count', [$this, 'getUserAnsweredCount']),
        ];
    }

    public function getUserAnsweredCount(User $user, Section $section)
    {
        return $this->quizRepository->getUserAnsweredCount($user, $section);
    }
}
