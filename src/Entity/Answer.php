<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 */
class Answer
{

    const CORRECT_MESSAGE = '正解！';
    const INCORRECT_MESSAGE= '残念！';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="answers")
     */
    private $user;

    /**
     * @var Quiz
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", inversedBy="answerText")
     */
    private $quiz;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $answerText;


    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isRight;


    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this;
     */
    public function setUser(User $user): Answer
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Quiz
     */
    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    /**
     * @param Quiz $quiz
     * @return $this;
     */
    public function setQuiz(Quiz $quiz): Answer
    {
        $this->quiz = $quiz;
        return $this;
    }

    /**
     * @return string
     */
    public function getAnswerText(): ?string
    {
        return $this->answerText;
    }

    /**
     * @param string $answerText
     * @return $this;
     */
    public function setAnswerText(string $answerText): Answer
    {
        $this->answerText = $answerText;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRight(): ?bool
    {
        return $this->isRight;
    }

    /**
     * @param bool $isRight
     * @return $this;
     */
    public function setIsRight(bool $isRight): Answer
    {
        $this->isRight = $isRight;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->isRight? self::CORRECT_MESSAGE: self::INCORRECT_MESSAGE;
    }

    public function grading()
    {
        if ($this->quiz){
            $this->setIsRight($this->quiz->getAnswerText() == $this->getAnswerText());
        } else {
            $this->setIsRight(false);
        }
    }
}
