<?php

namespace AdminBundle\Form\Type;


use AdminBundle\Entity\SentenceTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SentenceTranslationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Translation: ',
                'attr' => array(
                    'placeholder' => 'add translation ...',
                ),
            ))
            ->add('markedCorrect', CheckboxType::class, array(
                'label' => 'Is correct translation?',
            ));
    }
    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'sentence_translation';
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SentenceTranslation::class,
        ));
    }
}