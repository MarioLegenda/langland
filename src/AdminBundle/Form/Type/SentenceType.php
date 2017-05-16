<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\Sentence;
use AdminBundle\Form\Type\Generic\TraitType\TextTypeTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextareaTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SentenceType extends AbstractType
{
    use TextTypeTrait, TextareaTypeTrait;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildName($builder);
        $builder->add($this->createText('sentence', $builder));

        $builder
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