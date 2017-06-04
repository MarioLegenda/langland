<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\Game\QuestionGameAnswer;
use AdminBundle\Form\Type\Generic\TraitType\CheckboxTypeTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionGameAnswerType extends AbstractType
{
    use TextTypeTrait, CheckboxTypeTrait;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addTextType('name', $builder)
            ->addCheckboxType('isCorrect', $builder);
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
            'data_class' => QuestionGameAnswer::class,
        ));
    }
}