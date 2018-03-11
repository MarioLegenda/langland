<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PublicApi\Infrastructure\Type\CourseType;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Course implements ContainsLanguageInterface
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var string $whatToLearn
     */
    private $whatToLearn;
    /**
     * @var string $courseUrl
     */
    private $courseUrl;
    /**
     * @var Language $language
     */
    private $language;
    /**
     * @var int $courseOrder
     */
    private $courseOrder;
    /**
     * @var string $type
     */
    private $type;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * @var ArrayCollection $lessons
     */
    private $lessons;
    /**
     * Course constructor.
     */
    public function __construct()
    {
        $this->lessons = new ArrayCollection();
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set name
     *
     * @param string $name
     *
     * @return Course
     */
    public function setName($name) : Course
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param $whatToLearn
     * @return Course
     */
    public function setWhatToLearn($whatToLearn) : Course
    {
        $this->whatToLearn = $whatToLearn;

        return $this;
    }
    /**
     * @return string
     */
    public function getWhatToLearn()
    {
        return $this->whatToLearn;
    }
    /**
     * @return mixed
     */
    public function getCourseUrl()
    {
        return $this->courseUrl;
    }
    /**
     * @param mixed $courseUrl
     * @return Course
     */
    public function setCourseUrl($courseUrl) : Course
    {
        $this->courseUrl = $courseUrl;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }
    /**
     * @param mixed $language
     * @return Course
     */
    public function setLanguage($language) : Course
    {
        $this->language = $language;

        return $this;
    }
    /**
     * @return int
     */
    public function getCourseOrder()
    {
        return $this->courseOrder;
    }
    /**
     * @param int $courseOrder
     * @return Course
     */
    public function setCourseOrder(int $courseOrder): Course
    {
        $this->courseOrder = $courseOrder;

        return $this;
    }
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * @param string $type
     * @return Course
     */
    public function setType($type): Course
    {
        $this->type = $type;

        return $this;
    }
    /**
     * @return ArrayCollection
     */
    public function getLessons()
    {
        return $this->lessons;
    }
    /**
     * @param Lesson $lesson
     * @return bool
     */
    public function hasLesson(Lesson $lesson) : bool
    {
        return $this->lessons->contains($lesson);
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Course
     */
    public function setCreatedAt(\DateTime $createdAt) : Course
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * Get createdAt
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * @param \DateTime $updatedAt
     * @return Course
     */
    public function setUpdatedAt(\DateTime $updatedAt) : Course
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * @param Lesson $lesson
     * @return Course
     */
    public function addLesson(Lesson $lesson) : Course
    {
        if (!$this->hasLesson($lesson)) {
            $lesson->setCourse($this);
            $this->lessons->add($lesson);
        }

        return $this;
    }
    /**
     * @param ArrayCollection $lessons
     * @return Course
     */
    public function setLessons($lessons) : Course
    {
        foreach ($lessons as $lesson) {
            $this->addLesson($lesson);
        }

        return $this;
    }

    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }

    /**
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        $validTypes = CourseType::fromValue('Beginner')->toArray();

        if (!in_array($this->getType(), $validTypes)) {
            $message = sprintf('\'A type can be %s', implode(', ', $validTypes));
            $context->buildViolation($message)
                ->atPath('type')
                ->addViolation();
        }

        if (!is_int((int) $this->getCourseOrder())) {
            $context->buildViolation('Course order has to be an integer')
                ->atPath('courseOrder')
                ->addViolation();
        }
    }
}

