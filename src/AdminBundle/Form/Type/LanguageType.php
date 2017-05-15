<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\Language;
use AdminBundle\Form\Type\Generic\TraitType\NameTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageType extends AbstractType
{
    use NameTrait, TextTrait;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildName($builder);

        $builder
            ->add('showOnPage', CheckboxType::class)
            ->add('image', ImageType::class, array(
                'label' => false,
            ))
            ->add($this->createText($builder, 'listDescription'));
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