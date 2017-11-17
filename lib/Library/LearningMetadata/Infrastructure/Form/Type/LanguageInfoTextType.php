<?php

namespace Library\LearningMetadata\Infrastructure\Form\Type;

use Library\Infrastructure\Form\TraitType\TextTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Library\Infrastructure\Form\TraitType\TextareaTypeTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AdminBundle\Entity\LanguageInfoText;

class LanguageInfoTextType extends AbstractType
{
    use TextTypeTrait, TextareaTypeTrait;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addTextType('Language info name: ', 'name', $builder)
            ->addTextareaType('Language info text: ', 'text', $builder);
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
            'data_class' => LanguageInfoText::class,
        ));
    }
}