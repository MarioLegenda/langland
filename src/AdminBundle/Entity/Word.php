<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use LearningSystem\Infrastructure\Type\WordLevelType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Word implements ContainsCategoriesInterface, ContainsLanguageInterface
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
     * @var string $type
     */
    private $type;
    /**
     * @var int $language
     */
    private $language;
    /**
     * @var string $description
     */
    private $description;
    /**
     * @var int $level
     */
    private $level;
    /**
     * @var string $pluralForm
     */
    private $pluralForm;
    /**
     * @var ArrayCollection $categories
     */
    private $categories;
    /**
     * @var Translation[] $translations
     */
    private $translations;
    /**
     * @var Image $image
     */
    private $image;
    /**
     * @var Image $viewImage
     */
    private $viewImage;
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
        $this->categories = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param string $name
     * @return Word
     */
    public function setName($name) : Word
    {
        $this->name = $name;

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
     * @return Word
     */
    public function setLanguage($language) : Word
    {
        $this->language = $language;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Word
     */
    public function setType($type) : Word
    {
        $this->type = $type;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }
    /**
     * @param mixed $categories
     * @return Word
     */
    public function setCategories($categories) : Word
    {
        $this->categories = $categories;

        return $this;
    }
    /**
     * @param Translation $translation
     * @return bool
     */
    public function hasTranslation(Translation $translation) : bool
    {
        return $this->translations->contains($translation);
    }
    /**
     * @param Translation $translation
     * @return Word
     */
    public function addTranslation(Translation $translation) : Word
    {
        if (!$this->hasTranslation($translation)) {
            $translation->setWord($this);
            $this->translations->add($translation);
        }

        return $this;
    }
    /**
     * @param Translation $translation
     * @return Word
     */
    public function removeTranslation(Translation $translation) : Word
    {
        if ($this->hasTranslation($translation)) {
            $translation->setWord(null);
            $this->translations->removeElement($translation);
        }

        return $this;
    }
    /**
     * @return Translation[]|ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }
    /**
     * @return bool
     */
    public function hasImage() : bool
    {
        return $this->image->getImageFile() instanceof UploadedFile;
    }
    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }
    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @param mixed $description
     * @return Word
     */
    public function setDescription($description) : Word
    {
        $this->description = $description;

        return $this;
    }
    /**
     * @return int
     */
    public function getLevel(): ?int
    {
        return $this->level;
    }
    /**
     * @param int $level
     * @return Word
     */
    public function setLevel(int $level): Word
    {
        $this->level = $level;

        return $this;
    }
    /**
     * @return string
     */
    public function getPluralForm()
    {
        return $this->pluralForm;
    }
    /**
     * @param string $pluralForm
     * @return Word
     */
    public function setPluralForm(string $pluralForm) : Word
    {
        $this->pluralForm = $pluralForm;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @param \DateTime $createdAt
     * @return Word
     */
    public function setCreatedAt(\DateTime $createdAt) : Word
    {
        $this->createdAt = $createdAt;

        return $this;
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
     * @return Word
     */
    public function setUpdatedAt(\DateTime $updatedAt) : Word
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * @return Image
     */
    public function getViewImage()
    {
        return $this->viewImage;
    }
    /**
     * @param Image $viewImage
     */
    public function setViewImage($viewImage = null)
    {
        $this->viewImage = $viewImage;
    }
    /**
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (count($this->getTranslations()) > 25) {
            $context->buildViolation('There can be only up to 25 translations for a word')
                ->atPath('translations')
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