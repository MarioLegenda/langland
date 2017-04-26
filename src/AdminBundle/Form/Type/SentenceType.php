<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\Sentence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SentenceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Internal name: ',
                'attr' => array(
                    'placeholder' => '... click \'n type',
                    'autofocus' => true,
                ),
            ))
            ->add('sentence', TextareaType::class, array(
                'label' => 'Sentence: ',
                'attr' => array(
                    'placeholder' => '... click \'n type',
                    'autofocus' => true,
                    'rows' => 5,
                    'cols' => 40,
                ),
            ))
            ->add('sentenceTranslations', CollectionType::class, array(
                'label' => 'Add sentence translations ...',
                'entry_type' => SentenceTranslationType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ));
    }
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'sentence';
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Sentence::class,
        ));
    }
}