<?php

namespace AdminBundle\Entity\Game;

use AdminBundle\Entity\Lesson;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Game
 */
class WordGame
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
     * @var string $description
     */
    private $description;
    /**
     * @var string $url
     */
    private $url;
    /**
     * @var string $gameTypes
     */
    private $gameTypes;
    /**
     * @var Lesson $lesson
     */
    private $lesson;
    /**
     * @var ArrayCollection $gameUnits
     */
    private $gameUnits;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;

    public function __construct()
    {
        $this->gameUnits = new ArrayCollection();
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
     * @return WordGame
     */
    public function setName($name) : WordGame
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
     * @return mixed
     */
    public function getLesson()
    {
        return $this->lesson;
    }
    /**
     * @param mixed $lesson
     */
    public function setLesson($lesson)
    {
        $this->lesson = $lesson;
    }
    /**
     * Set description
     *
     * @param string $description
     *
     * @return WordGame
     */
    public function setDescription($description) : WordGame
    {
        $this->description = $description;

        return $this;
    }
    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }
    /**
     * @param mixed $url
     * @return WordGame
     */
    public function setUrl($url) : WordGame
    {
        $this->url = $url;

        return $this;
    }
    /**
     * @return string
     */
    public function getGameTypes(): string
    {
        return $this->gameTypes;
    }
    /**
     * @param string $gameTypes
     * @return WordGame
     */
    public function setGameTypes(string $gameTypes) : WordGame
    {
        $this->gameTypes = $gameTypes;

        return $this;
    }
    /**
     * @param WordGameUnit $gameUnit
     * @return bool
     */
    public function hasGameUnit(WordGameUnit $gameUnit) : bool
    {
        return $this->gameUnits->contains($gameUnit);
    }
    /**
     * @param WordGameUnit $gameUnit
     * @return WordGame
     */
    public function addGameUnit(WordGameUnit $gameUnit) : WordGame
    {
        if (!$this->hasGameUnit($gameUnit)) {
            $gameUnit->setGame($this);
            $this->gameUnits->add($gameUnit);
        }

        return $this;
    }
    /**
     * @param WordGameUnit $gameUnit
     * @return WordGame
     */
    public function removeGameUnit(WordGameUnit $gameUnit) : WordGame
    {
        if ($this->hasGameUnit($gameUnit)) {
            $this->gameUnits->removeElement($gameUnit);
        }

        return $this;
    }
    /**
     * @return mixed
     */
    public function getGameUnits()
    {
        return $this->gameUnits;
    }
    /**
     * @param mixed $gameUnits
     * @return WordGame
     */
    public function setGameUnits($gameUnits) : WordGame
    {
        $this->gameUnits = $gameUnits;

        return $this;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return WordGame
     */
    public function setCreatedAt($createdAt) : WordGame
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return WordGame
     */
    public function setUpdatedAt($updatedAt) : WordGame
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (count($this->getGameUnits()) === 0) {
            $context->buildViolation('There has to be at least one game unit present')
                ->atPath('name')
                ->addViolation();
        }
    }
    /**
     * @param array $data
     * @return WordGame
     */
    public static function create(array $data) : WordGame
    {
        $game = new WordGame();

        if (array_key_exists('name', $data)) {
            $game->setName($data['name']);
        }

        if (array_key_exists('description', $data)) {
            $game->setDescription($data['description']);
        }

        return $game;
    }

    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}

