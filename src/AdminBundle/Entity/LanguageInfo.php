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
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
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
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
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

        $languageInfoTexts = $this->getLanguageInfoTexts();

        if (!$languageInfoTexts->isEmpty()) {
            /** @var LanguageInfoText $languageInfoText */
            foreach ($languageInfoTexts as $languageInfoText) {
                if (empty($languageInfoText->getName()) or empty($languageInfoText->getText())) {
                    $message = null;

                    if (!empty($languageInfoText->getName())) {
                        $message = sprintf('Language info text with name \'%s\' is has empty text', $languageInfoText->getName());
                    } else {
                        $message = 'There can not be any language infos with empty text or name';
                    }

                    $context->buildViolation($message)
                        ->atPath('languageInfoTexts')
                        ->addViolation();
                }
            }
        }
    }
}

