<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Form\Type\Generic\TraitType\CategoryChoiceTrait;
use AdminBundle\Form\Type\Generic\TraitType\ImageTypeTrait;
use AdminBundle\Form\Type\Generic\TraitType\LanguageChoiceTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextareaTypeTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextTypeTrait;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AdminBundle\Entity\Word;

class WordType extends AbstractType
{
    use LanguageChoiceTrait, TextTypeTrait, TextareaTypeTrait, ImageTypeTrait, CategoryChoiceTrait;
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * WordType constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $word = $options['word'];

        $this
            ->addTextType('name', $builder)
            ->addTextType('type', $builder)
            ->addTextareaType('description', $builder)
            ->addImageType('image', $builder)
            ->addLanguageChoice($builder, $this->em, $word)
            ->addCategoryChoice($builder, $this->em, $word);

        $builder
            ->add('translations', CollectionType::class, array(
                'label' => 'Add translations ...',
                'entry_type' => TranslationType::class,
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
        return 'form';
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Word::class,
        ));

        $resolver->setRequired('word');
    }
}