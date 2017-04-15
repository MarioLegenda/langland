<?php

namespace AdminBundle\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Sentence
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var int $lessonId
     */
    private $lessonId;
    /**
     * @var string $internalName
     */
    private $internalName;
    /**
     * @var string $sentence
     */
    private $sentence;
    /**
     * @var array $translations
     */
    private $translations = array();
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $id
     * @return Sentence
     */
    public function setId($id) : Sentence
    {
        $this->id = $id;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getLessonId()
    {
        return $this->lessonId;
    }
    /**
     * @param mixed $lessonId
     * @return Sentence
     */
    public function setLessonId($lessonId) : Sentence
    {
        $this->lessonId = $lessonId;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getSentence()
    {
        return $this->sentence;
    }
    /**
     * @param mixed $sentence
     * @return Sentence
     */
    public function setSentence($sentence) : Sentence
    {
        $this->sentence = $sentence;

        return $this;
    }
    /**
     * @return array
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }
    /**
     * @param array $translations
     * @return Sentence
     */
    public function setTranslations(array $translations) : Sentence
    {
        $this->translations = $translations;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getInternalName()
    {
        return $this->internalName;
    }
    /**
     * @param mixed $internalName
     * @return Sentence
     */
    public function setInternalName($internalName) : Sentence
    {
        $this->internalName = $internalName;

        return $this;
    }
    /**
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (count($this->getTranslations()) === 0) {
            $context->buildViolation('There has to be at least one translation for a sentence')
                ->atPath('translations')
                ->addViolation();
        }
    }
    /**
     * @param Request $request
     * @return Sentence
     */
    public static function createFromRequest(Request $request) : Sentence
    {
        $sentence = new Sentence();

        $request = $request->request;

        $sentence
            ->setId(($request->has('id')) ? $request->get('id') : null)
            ->setInternalName(($request->has('internal_name')) ? $request->get('internal_name') : null)
            ->setLessonId($request->get('lesson_id'))
            ->setSentence(($request->has('sentence')) ? $request->get('sentence') : null)
            ->setTranslations(($request->has('translations')) ? $request->get('translations') : array());

        return $sentence;
    }
}