<?php

namespace AdminBundle\Form\Type\Generic\TraitType;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

trait TextTrait
{
    /**
     * @param FormBuilderInterface $builder
     * @return $this
     */
    public function buildText(FormBuilderInterface $builder)
    {
        $builder
            ->add('text', TextareaType::class, array(
                'label' => 'Text: ',
                'attr' => array(
                    'placeholder' => 'Type your text...',
                    'rows' => 5,
                    'cols' => 60,
                ),
            ));

        return $this;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param string $name
     * @return FormBuilderInterface
     */
    public function createText(string $name, FormBuilderInterface $builder) : FormBuilderInterface
    {
        return $builder
            ->create($name, TextareaType::class, array(
                'label' => 'Text: ',
                'attr' => array(
                    'placeholder' => 'Type your text...',
                    'rows' => 5,
                    'cols' => 60,
                ),
            ));
    }
}