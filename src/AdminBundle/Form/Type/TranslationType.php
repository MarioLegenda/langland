<?php

namespace AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Length;

class TranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translation', TextType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => '... add translation',
                ),
                'constraints' => array(
                    new Length(array(
                        'max' => 50,
                        'maxMessage' => 'Word translation can have up to {{ limit }} characters',
                    )),
                )
            )
        );
    }

    public function getBlockPrefix()
    {
        return 'translation';
    }
}