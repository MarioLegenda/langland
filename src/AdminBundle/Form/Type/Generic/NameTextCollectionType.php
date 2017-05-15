<?php

namespace AdminBundle\Form\Type\Generic;

use AdminBundle\Form\Type\Generic\TraitType\NameTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NameTextCollectionType extends AbstractType
{
    use NameTrait, TextTrait;
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
            ->buildText($builder)
            ->buildName($builder);
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