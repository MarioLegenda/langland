<?php

namespace LearningMetadata\Infrastructure\Form\Type;

use AdminBundle\Entity\Language;
use Library\Infrastructure\Form\TraitType\CheckboxTypeTrait;
use Library\Infrastructure\Form\TraitType\ImageTypeTrait;
use Library\Infrastructure\Form\TraitType\TextareaTypeTrait;
use Library\Infrastructure\Form\TraitType\TextTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageType extends AbstractType
{
    use TextTypeTrait, TextareaTypeTrait, CheckboxTypeTrait, ImageTypeTrait;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addTextType('Language name: ', 'name', $builder)
            ->addCheckboxType('Show on page', 'showOnPage', $builder)
            ->addTextareaType('Description', 'listDescription', $builder)
            ->addImageType('image', $builder);
    }
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'form';
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Language::class,
        ));
    }
}