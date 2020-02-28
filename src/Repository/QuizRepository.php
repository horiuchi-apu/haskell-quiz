<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Quiz;
use App\Entity\Section;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function getUserAnsweredCount(User $user, Section $section = null)
    {
        $qb = $this->createQueryBuilder('quiz')
            ->select('COUNT(DISTINCT(quiz))')
            ->leftJoin('quiz.answers', 'answers')
            ->where('answers.user=:user')
            ->setParameter('user', $user)
        ;

        if ($section) {
            $qb->andWhere('quiz.section=:section')
                ->setParameter('section', $section);
        }

        return $qb->getQuery()->getResult()[0][1];
    }

    /**
     * @param $name
     * @return Quiz|null
     */
    public function findByLikeFunctionName($name)
    {
        $qb = $this->createQueryBuilder('quiz')
            ->where('quiz.quizText LIKE :name')
            ->setParameter('name', '%'. $name .'%')
        ;

        return $qb->getQuery()->getResult();
    }
}
