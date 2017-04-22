<?php

namespace AdminBundle\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class MultipleChoiceTransformer implements DataTransformerInterface
{
    /**
     * @var object[] $objects
     */
    private $objects;
    /**
     * @var $repository
     */
    private $repository;
    /**
     * MultipleChoiceTransformer constructor.
     * @param array $objects
     * @param $repository
     */
    public function __construct($objects, $repository)
    {
        $this->objects = $objects;
        $this->repository = $repository;
    }

    /**
     * @param mixed $value
     * @return array
     */
    public function transform($value)
    {
        $values = array();

        foreach ($this->objects as $object) {
            $values[] = $object->getId();
        }

        return $values;
    }

    /**
     * @param mixed $ids
     * @return array
     */
    public function reverseTransform($ids)
    {
        $values = array();
        foreach ($ids as $id) {
            $values[] = $this->repository->find($id);
        }

        return $values;
    }
}