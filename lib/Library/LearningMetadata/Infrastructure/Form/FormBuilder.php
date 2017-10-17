<?php

namespace Library\LearningMetadata\Infrastructure\Form;

use AdminBundle\Entity\Language;
use Library\Infrastructure\Form\FormBuilderInterface;
use Library\LearningMetadata\Infrastructure\Form\Type\LanguageType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormBuilder implements FormBuilderInterface
{
    /**
     * @var FormFactoryInterface $formFactory
     */
    private $formFactory;
    /**
     * FormBuilder constructor.
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }
    /**
     * @inheritdoc
     */
    public function getForm(string $type, $data = null, array $options = array()) : FormInterface
    {
        return $this->formFactory->create($type, $data, $options);
    }
}