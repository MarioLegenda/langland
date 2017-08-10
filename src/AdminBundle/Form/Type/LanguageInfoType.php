<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\LanguageInfo;
use AdminBundle\Form\Type\Generic\LanguageChoiceFormService;
use AdminBundle\Form\Type\Generic\TraitType\LanguageChoiceTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class LanguageInfoType extends AbstractType
{
    use TextTypeTrait;
    /**
     * @var LanguageChoiceFormService $em
     */
    private $languageChoiceService;
    /**
     * WordType constructor.
     * @param LanguageChoiceFormService $languageChoiceFormService
     */
    public function __construct(LanguageChoiceFormService $languageChoiceFormService)
    {
        $this->languageChoiceService = $languageChoiceFormService;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addTextType('Name: ', 'name', $builder);

        $this->languageChoiceService->getLanguageChoice(
            'LanguageInfo',
            $builder
        );

        $builder->add('languageInfoTexts', CollectionType::class, array(
            'label' => 'Add language text: ',
            'entry_type' => LanguageInfoTextType::class,
            'allow_add' => true,
            'by_reference' => false,
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
            'data_class' => LanguageInfo::class,
        ));
    }
}