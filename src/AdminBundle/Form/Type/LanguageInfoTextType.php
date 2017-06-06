<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\LanguageInfoText;
use AdminBundle\Form\Type\Generic\TraitType\TextTypeTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextareaTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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