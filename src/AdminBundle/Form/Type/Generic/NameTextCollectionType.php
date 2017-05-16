<?php

namespace AdminBundle\Form\Type\Generic;

use AdminBundle\Form\Type\Generic\TraitType\TextTypeTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextareaTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NameTextCollectionType extends AbstractType
{
    use TextTypeTrait, TextareaTypeTrait;
    /**
     * @var string $dataClass
     */
    private $dataClass;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->buildName($builder)
            ->buildText($builder);
    }
    /**
     * NameTextCollectionType constructor.
     * @param string $dataClass
     */
    public function __construct(string $dataClass)
    {
        $this->dataClass = $dataClass;
    }
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'nameText';
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass,
        ));
    }
}