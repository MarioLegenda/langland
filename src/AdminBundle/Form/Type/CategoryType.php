<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Validator\Constraint\CategoryExistsConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', TextType::class, array(
                'label' => 'Category: ',
                'attr' => array(
                    'placeholder' => '... click \'n type',
                ),
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Category name cannot be empty',
                    )),
                    new Length(array(
                        'max' => 50,
                        'maxMessage' => 'Category name can have up to {{ limit }} characters',
                    )),
                    new CategoryExistsConstraint(),
                ),
            ));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}