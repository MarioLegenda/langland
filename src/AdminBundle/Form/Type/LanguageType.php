<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\Language;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Language: ',
                'attr' => array(
                    'placeholder' => '... click \'n type',
                    'autofocus' => true,
                ),
            ))
            ->add('showOnPage', CheckboxType::class)
            ->add('languageIcon', ImageType::class, array(
                'label' => false,
            ))
            ->add('listDescription', TextareaType::class, array(
                'label' => 'Frontend list description ...',
                'attr' => array(
                    'placeholder' => '... this description goes in language courses list',
                    'rows' => 5,
                    'cols' => 40,
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
            'data_class' => Language::class,
        ));
    }
}