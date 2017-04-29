<?php

namespace AdminBundle\Form\Type\View;

use AdminBundle\Entity\ViewEntity\WordPool;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WordPoolType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Internal name: ',
                'attr' => array(
                    'placeholder' => '... internal name',
                ),
            ))
            ->add('wordIds', TextType::class, array(
                'label' => false,
                'attr' => array(
                    'data-autocomplete-widget' => 'true',
                ),
            ));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => WordPool::class,
        ));
    }
}