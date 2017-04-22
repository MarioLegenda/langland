<?php

namespace AdminBundle\Transformer;

use AdminBundle\Repository\LanguageRepository;
use Symfony\Component\Form\DataTransformerInterface;

class SingleChoiceTransformer implements DataTransformerInterface
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var $repository
     */
    private $repository;
    /**
     * SingleChoiceTransformer constructor.
     * @param $id
     * @param $repository
     */
    public function __construct($id, $repository)
    {
        $this->id = $id;
        $this->repository = $repository;
    }
    /**
     * @param mixed $issue
     * @return int
     */
    public function transform($issue)
    {
        return $this->id;
    }
    /**
     * @param mixed $value
     * @return mixed
     */
    public function reverseTransform($value)
    {
        if (is_null($value)) {
            return null;
        }

        return $this->repository->find($value);
    }
}