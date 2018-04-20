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
 * @ORM\Entity(repositoryClass="App\Repository\SectionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Section
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
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Quiz", mappedBy="section")
     */
    private $quizzes;

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
        $this->quizzes = new ArrayCollection();
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): ?Section
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): Section
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): Section
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getQuizzes()
    {
        return $this->quizzes;
    }

    /**
     * @param ArrayCollection $quizzes
     * @return $this
     */
    public function setQuizzes(ArrayCollection $quizzes): ?Section
    {
        $this->quizzes = $quizzes;
        return $this;
    }

    public function addQuiz(Quiz $quiz): ?Section
    {
        $this->quizzes->add($quiz);
        $quiz->setSection($this);
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
    public function setCreated(\DateTime $created): Section
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
    public function setModified(\DateTime $modified): Section
    {
        $this->modified = $modified;
        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateModifiedDatetime() {
        $this->setModified(new \DateTime());
    }
}
