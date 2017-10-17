<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * LanguageInfo
 */
class LanguageInfo implements ContainsLanguageInterface
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
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * @var bool $isLooked
     */
    private $isLooked;
    /**
     * @var Language $language
     */
    private $language;
    /**
     * @var ArrayCollection $languageInfoTexts
     */
    private $languageInfoTexts;

    public function __construct()
    {
        $this->languageInfoTexts = new ArrayCollection();
        $this->isLooked = false;
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
     * @return LanguageInfo
     */
    public function setName($name) : LanguageInfo
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return LanguageInfo
     */
    public function setCreatedAt($createdAt) : LanguageInfo
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
     * @return LanguageInfo
     */
    public function setUpdatedAt($updatedAt) : LanguageInfo
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
     * Set isLooked
     *
     * @param boolean $isLooked
     *
     * @return LanguageInfo
     */
    public function setIsLooked($isLooked) : LanguageInfo
    {
        $this->isLooked = $isLooked;

        return $this;
    }
    /**
     * Get isLooked
     *
     * @return bool
     */
    public function getIsLooked()
    {
        return $this->isLooked;
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
     * @return LanguageInfo
     */
    public function setLanguage($language) : LanguageInfo
    {
        $this->language = $language;

        return $this;
    }
    /**
     * @param LanguageInfoText $text
     * @return bool
     */
    public function hasLanguageInfoText(LanguageInfoText $text) : bool
    {
        return $this->languageInfoTexts->contains($text);
    }
    /**
     * @param LanguageInfoText $text
     * @return LanguageInfo
     */
    public function addLanguageInfoText(LanguageInfoText $text) : LanguageInfo
    {
        if (!$this->hasLanguageInfoText($text)) {
            $text->setLanguageInfo($this);
            $this->languageInfoTexts->add($text);
        }

        return $this;
    }
    /**
     * @param $texts
     */
    public function setLanguageInfoTexts($texts)
    {
        $this->languageInfoTexts = $texts;
    }
    /**
     * @param LanguageInfoText $text
     * @return LanguageInfo
     */
    public function removeLanguageInfoText(LanguageInfoText $text) : LanguageInfo
    {
        if ($this->hasLanguageInfoText($text)) {
            $this->languageInfoTexts->removeElement($text);
        }

        return $this;
    }
    /**
     * @return ArrayCollection
     */
    public function getLanguageInfoTexts()
    {
        return $this->languageInfoTexts;
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
        if (count($this->getLanguageInfoTexts()) === 0) {
            $context->buildViolation('There has to be at least on text info')
                ->atPath('languageInfoTexts')
                ->addViolation();
        }
    }
}

