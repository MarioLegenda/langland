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
                    'placeholder' => 'Email',
                ),
            ))
            ->add('name', TextType::class, array(
                'label' => 'NAME',
                'attr' => array(
                    'placeholder' => 'Name',
                ),
            ))
            ->add('lastname', TextType::class, array(
                'label' => 'LAST NAME',
                'attr' => array(
                    'placeholder' => 'Last name',
                ),
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Passwords do not match',
                'first_options'  => array(
                    'label' => 'PASSWORD',
                    'attr' => array(
                        'placeholder' => 'Password'
                    )
                ),
                'second_options' => array(
                    'label' => 'REPEAT PASSWORD',
                    'attr' => array(
                        'placeholder' => 'Repeat password'
                    )
                ),
            ))
            ->add('gender', ChoiceType::class, array(
                'label' => 'GENDER',
                'multiple' => false,
                'placeholder' => 'Choose...',
                'choices' => array(
                    'male' => 'male',
                    'female' => 'female',
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