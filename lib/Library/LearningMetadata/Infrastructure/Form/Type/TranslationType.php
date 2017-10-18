<?php

namespace Library\LearningMetadata\Infrastructure\Form\Type;

use AdminBundle\Entity\Translation;
use AdminBundle\Form\Type\Generic\TraitType\TextTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationType extends AbstractType
{
    use TextTypeTrait;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addTextType('Translation: ', 'name', $builder);
    }
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'translation';
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Translation::class
        ));
    }
}