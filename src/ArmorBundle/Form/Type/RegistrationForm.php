<?php

namespace ArmorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use ArmorBundle\Entity\User;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', EmailType::class, array(
                'label' => 'EMAIL',
                'attr' => array(
                    'placeholder' => '...',
                ),
            ))
            ->add('name', TextType::class, array(
                'label' => 'NAME',
                'attr' => array(
                    'placeholder' => '...',
                ),
            ))
            ->add('lastname', TextType::class, array(
                'label' => 'LAST NAME',
                'attr' => array(
                    'placeholder' => '...',
                ),
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Passwords do not match',
                'first_options'  => array(
                    'label' => 'PASSWORD',
                    'attr' => array(
                        'placeholder' => '...'
                    )
                ),
                'second_options' => array(
                    'label' => 'REPEAT PASSWORD',
                    'attr' => array(
                        'placeholder' => '...'
                    )
                ),
            ));
        ;
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}