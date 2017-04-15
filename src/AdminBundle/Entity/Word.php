<?php

namespace AdminBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Word
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $word
     */
    private $word;
    /**
     * @var string $category
     */
    private $category;
    /**
     * @var string $type
     */
    private $type;
    /**
     * @var int $language
     */
    private $language;
    /**
     * @var array $translations
     */
    private $translations = array();
    /**
     * @var File|mixed $image
     */
    private $image;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getWord()
    {
        return $this->word;
    }
    /**
     * @param mixed $word
     */
    public function setWord($word)
    {
        $this->word = $word;
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
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }
    /**
     * @return mixed
     */
    public function getTranslations()
    {
        return $this->translations;
    }
    /**
     * @param string $translation
     * @return $this
     */
    public function addTranslation($translation)
    {
        $this->translations[] = $translation;

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
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
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
    public function setImage(File $image)
    {
        $this->image = $image;
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
    /**
     * @param Request $request
     * @return Word
     */
    public static function createFromRequest(Request $request) : Word
    {
        $word = new Word();

        $word->setWord($request->request->get('word'));
        $word->setType($request->request->get('type'));
        $word->setLanguage($request->request->get('language_id'));
        $word->setCategory($request->request->get('category'));

        $translations = $request->request->get('translations');
        foreach ($translations as $translation) {
            $word->addTranslation($translation);
        }

        if ($request->files->has('image')) {
            $word->setImage($request->files->get('image'));
        }

        return $word;
    }
    /**
     * @return array
     */
    public function toArray() : array
    {
        return array(
            'word' => $this->getWord(),
            'type' => $this->getType(),
            'language_id' => $this->getLanguage(),
            'category_id' => $this->getCategory(),
            'translations' => $this->getTranslations(),
            'image' => $this->getImage(),
        );
    }
}