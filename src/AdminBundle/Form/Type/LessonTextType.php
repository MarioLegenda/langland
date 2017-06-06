<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\LessonText;
use AdminBundle\Form\Type\Generic\TraitType\TextareaTypeTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessonTextType extends AbstractType
{
    use TextTypeTrait, TextareaTypeTrait;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addTextType('Name: ', 'name', $builder)
            ->addTextareaType('Text: ', 'text', $builder);
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
            'data_class' => LessonText::class,
        ));
    }
}