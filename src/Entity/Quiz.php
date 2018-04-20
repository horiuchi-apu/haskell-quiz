<?php
/**
 * Created by PhpStorm.
 * User: Horiuchi
 * Date: 2018/04/08
 * Time: 15:39
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Quiz
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $quizText;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $answerText;

    /**
     * @var Section
     * @ORM\ManyToOne(targetEntity="App\Entity\Section", inversedBy="quizzes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $section;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="quiz")
     */
    private $answers;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $page;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $modified;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->setCreated(new \DateTime());
        $this->setModified(new \DateTime());
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getQuizText(): ?string
    {
        return $this->quizText;
    }

    /**
     * @param string $quizText
     * @return $this
     */
    public function setQuizText(string $quizText): ?Quiz
    {
        $this->quizText = $quizText;
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
     * @return $this
     */
    public function setAnswerText(string $answerText): ?Quiz
    {
        $this->answerText = $answerText;
        return $this;
    }

    /**
     * @return Section
     */
    public function getSection(): ?Section
    {
        return $this->section;
    }

    /**
     * @param Section $section
     * @return $this
     */
    public function setSection(Section $section): ?Quiz
    {
        $this->section = $section;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param ArrayCollection $answers
     * @return $this
     */
    public function setAnswers(ArrayCollection $answers): ?Quiz
    {
        $this->answers = $answers;
        return $this;
    }

    /**
     * @return string
     */
    public function getPage(): ?string
    {
        return $this->page;
    }

    /**
     * @param string $page
     * @return $this
     */
    public function setPage(string $page): ?Quiz
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return  $this
     */
    public function setCreated(\DateTime $created): Quiz
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getModified(): \DateTime
    {
        return $this->modified;
    }

    /**
     * @param \DateTime $modified
     * @return  $this
     */
    public function setModified(\DateTime $modified): Quiz
    {
        $this->modified = $modified;
        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateModifiedDatetime() {
        $this->setModified(new \DateTime());
    }
}
