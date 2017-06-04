<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * Sentence
 */
class Sentence
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
     * @var string $sentence
     */
    private $sentence;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * @var Course $course
     */
    private $course;
    /**
     * @var ArrayCollection $sentenceTranslations
     */
    private $sentenceTranslations;

    public function __construct()
    {
        $this->sentenceTranslations = new ArrayCollection();
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
     * @return Sentence
     */
    public function setName($name) : Sentence
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
     * Set sentence
     *
     * @param string $sentence
     *
     * @return Sentence
     */
    public function setSentence($sentence) : Sentence
    {
        $this->sentence = $sentence;

        return $this;
    }
    /**
     * Get sentence
     *
     * @return string
     */
    public function getSentence()
    {
        return $this->sentence;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Sentence
     */
    public function setCreatedAt(\DateTime $createdAt) : Sentence
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
     * @return Sentence
     */
    public function setUpdatedAt(\DateTime $updatedAt) : Sentence
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getCourse()
    {
        return $this->course;
    }
    /**
     * @param mixed $course
     * @return Sentence
     */
    public function setCourse($course) : Sentence
    {
        $this->course = $course;

        return $this;
    }
    /**
     * @param SentenceTranslation $sentenceTranslation
     * @return bool
     */
    public function hasSentenceTranslation(SentenceTranslation $sentenceTranslation) : bool
    {
        return $this->sentenceTranslations->contains($sentenceTranslation);
    }
    /**
     * @param SentenceTranslation $sentenceTranslation
     * @return Sentence
     */
    public function addSentenceTranslation(SentenceTranslation $sentenceTranslation) : Sentence
    {
        if (!$this->hasSentenceTranslation($sentenceTranslation)) {
            $sentenceTranslation->setSentence($this);
            $this->sentenceTranslations->add($sentenceTranslation);
        }

        return $this;
    }
    /**
     * @param SentenceTranslation $sentenceTranslation
     * @return Sentence
     */
    public function removeSentenceTranslation(SentenceTranslation $sentenceTranslation) : Sentence
    {
        if ($this->hasSentenceTranslation($sentenceTranslation)) {
            $sentenceTranslation->setSentence(null);
            $this->sentenceTranslations->removeElement($sentenceTranslation);
        }

        return $this;
    }
    /**
     * @param $sentenceTranslations
     * @return Sentence
     */
    public function setSentenceTranslations($sentenceTranslations) : Sentence
    {
        foreach ($sentenceTranslations as $sentenceTranslation) {
            $this->addSentenceTranslation($sentenceTranslation);
        }

        return $this;
    }
    /**
     * @return ArrayCollection
     */
    public function getSentenceTranslations()
    {
        return $this->sentenceTranslations;
    }
    /**
     * @param ExecutionContext $context
     */
    public function validate(ExecutionContext $context)
    {
        if (count($this->sentenceTranslations) < 3) {
            $context->buildViolation('You have to provide at least 3 non empty translations for a sentence and one of them must be marked as correct')
                ->atPath('sentenceTranslations')
                ->addViolation();

            return;
        }

        $hasName = 0;
        foreach ($this->sentenceTranslations as $sentenceTranslation) {
            if (is_string($sentenceTranslation->getName())) {
                ++$hasName;
            }
        }

        if ($hasName < 3) {
            $context->buildViolation('You have to provide at least 3 non empty translations for a sentence and one of them must be marked as correct')
                ->atPath('sentenceTranslations')
                ->addViolation();
        }

        $correctNum = 0;
        foreach ($this->sentenceTranslations as $sentenceTranslation) {
            $markedCorrect = $sentenceTranslation->getMarkedCorrect();

            if ($markedCorrect === true) {
                ++$correctNum;
            }
        }

        if ($correctNum !== 1) {
            $context->buildViolation('You have to mark a single sentence translation as correct')
                ->atPath('sentenceTranslations')
                ->addViolation();
        }
    }

    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}

