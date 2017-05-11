<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\LanguageInfoText;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LanguageInfoTextType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'This will be rendered as a heading'
                )
            ))
            ->add('text', TextareaType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'This will be rendered as text',
                    'rows' => 5,
                    'cols' => 60,
                ),
            ));
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