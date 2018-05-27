<?php

namespace PublicApi\Infrastructure\Model;

use Doctrine\Common\Collections\ArrayCollection;

class Course
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
     * Course constructor.
     * @param int $id
     * @param string $name
     * @param string $whatToLearn
     * @param string $courseUrl
     * @param int $courseOrder
     * @param string $type
     * @param \DateTime $createdAt
     * @param \DateTime $updatedAt
     */
    public function __construct(
        int $id,
        string $name,
        string $whatToLearn,
        string $courseUrl,
        int $courseOrder,
        string $type,
        \DateTime $createdAt,
        \DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->whatToLearn = $whatToLearn;
        $this->courseUrl = $courseUrl;
        $this->courseOrder = $courseOrder;
        $this->type = $type;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
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
     * @return \DateTime
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
}