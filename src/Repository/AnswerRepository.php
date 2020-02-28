<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Quiz;
use App\Entity\User;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    public function isContinuousPost(Answer $answer, User $user, Quiz $quiz)
    {
        $qb = $this->createQueryBuilder('answer')
            ->andWhere('answer.user=:user')
            ->andWhere('answer.quiz=:quiz')
            ->setParameter('user', $user)
            ->setParameter('quiz', $quiz)
            ->orderBy('answer.created', 'DESC')
            ->setMaxResults(1)
        ;


        $result = $qb->getQuery()->getResult();
        if (empty($result)) {
            return false;
        }

        /** @var Answer $result */
        $result = $result[0];
        $limit = Carbon::now()->addMinute(-1);

        return $result->getAnswerText() == $answer->getAnswerText() && $result->getCreated() > $limit;
    }
}
