<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Form\Type\Generic\CategoryChoiceFormService;
use AdminBundle\Form\Type\Generic\TraitType\ImageTypeTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextareaTypeTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AdminBundle\Entity\Word;
use AdminBundle\Form\Type\Generic\LanguageChoiceFormService;

class WordType extends AbstractType
{
    use TextTypeTrait, TextareaTypeTrait, ImageTypeTrait;
    /**
     * @var CategoryChoiceFormService
     */
    private $categoryChoiceFormService;
    /**
     * @var LanguageChoiceFormService $em
     */
    private $languageChoiceService;
    /**
     * WordType constructor.
     * @param LanguageChoiceFormService $languageChoiceFormService
     * @param CategoryChoiceFormService $categoryChoiceFormService
     */
    public function __construct(
        LanguageChoiceFormService $languageChoiceFormService,
        CategoryChoiceFormService $categoryChoiceFormService
    ) {
        $this->languageChoiceService = $languageChoiceFormService;
        $this->categoryChoiceFormService = $categoryChoiceFormService;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addTextType('Word name:', 'name', $builder)
            ->addTextType('Word type: ', 'type', $builder)
            ->addTextareaType('Word description', 'description', $builder)
            ->addTextType('Plural form: ', 'pluralForm', $builder)
            ->addImageType('image', $builder);

        $this->languageChoiceService->getLanguageChoice(
            'Word',
            $builder
        );

        $this->categoryChoiceFormService->addCategoryChoice(
            'Word',
            $builder
        );

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
    }
}