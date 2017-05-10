<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\Language;
use AdminBundle\Entity\LanguageInfo;
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

        $builder
            ->add('language', ChoiceType::class, array(
                'label' => 'Language: ',
                'placeholder' => 'Choose language',
                'choices' => $this->createLanguageChoices(),
                'choice_label' => function($choice, $key, $value) {
                    return ucfirst($key);
                }
            ))
            ->add('name', TextType::class, array(
                'label' => 'Info name: ',
                'attr' => array(
                    'placeholder' => 'This will be rendered as a heading for this info',
                ),
            ))
            ->add('languageInfoTexts', CollectionType::class, array(
                'label' => 'Add text ...',
                'entry_type' => LanguageInfoTextType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ));

        $builder->get('language')->addModelTransformer(new SingleChoiceTransformer(
            ($languageInfo->getLanguage()) instanceof Language ? $languageInfo->getLanguage()->getId() : null,
            $this->em->getRepository('AdminBundle:Language')
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

    private function createLanguageChoices()
    {
        $languages = $this->em->getRepository('AdminBundle:Language')->findAll();

        $choices = array();

        foreach ($languages as $language) {
            $choices[$language->getName()] = $language->getId();
        }

        return $choices;
    }
}