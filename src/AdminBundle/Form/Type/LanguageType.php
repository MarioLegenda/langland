<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Validator\Constraint\LanguageExistsConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LanguageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('language', TextType::class, array(
                'label' => 'Language: ',
                'attr' => array(
                    'placeholder' => '... click \'n type',
                ),
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Language name cannot be empty',
                    )),
                    new Length(array(
                        'max' => 50,
                        'maxMessage' => 'Language name can have up to {{ limit }} characters',
                    )),
                    new LanguageExistsConstraint(),
                ),
            ));
    }

    public function getBlockPrefix()
    {
        return 'language';
    }
}