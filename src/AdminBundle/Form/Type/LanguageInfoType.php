<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\LanguageInfo;
use AdminBundle\Form\Type\Generic\TraitType\LanguageChoiceTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextTypeTrait;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class LanguageInfoType extends AbstractType
{
    use TextTypeTrait, LanguageChoiceTrait;
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
        $languageInfo = $options['languageInfo'];

        $this
            ->addTextType('name', $builder)
            ->addLanguageChoice($builder, $this->em, $languageInfo);

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

        $resolver->setRequired('languageInfo');
    }
}