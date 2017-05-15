<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\Language;
use AdminBundle\Entity\LanguageInfo;
use AdminBundle\Form\Type\Generic\NameTextCollectionType;
use AdminBundle\Form\Type\Generic\TraitType\LanguageChoiceTrait;
use AdminBundle\Form\Type\Generic\TraitType\NameTextTrait;
use AdminBundle\Form\Type\Generic\TraitType\NameTrait;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use AdminBundle\Transformer\SingleChoiceTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class LanguageInfoType extends AbstractType
{
    use NameTrait, NameTextTrait, LanguageChoiceTrait;
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
            ->buildName($builder)
            ->buildLanguageChoice($builder, $this->em, $languageInfo);

        $builder->add($this->createNameText('languageInfoTexts', $builder));
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