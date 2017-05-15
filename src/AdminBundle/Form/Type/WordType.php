<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\Language;
use AdminBundle\Form\Type\Generic\TraitType\LanguageChoiceTrait;
use AdminBundle\Form\Type\Generic\TraitType\NameTrait;
use AdminBundle\Transformer\MultipleChoiceTransformer;
use AdminBundle\Transformer\SingleChoiceTransformer;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AdminBundle\Entity\Word;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class WordType extends AbstractType
{
    use LanguageChoiceTrait, NameTrait;
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
            ->buildName($builder)
            ->buildLanguageChoice($builder, $this->em, $word);

        $builder
            ->add('type', TextType::class, array(
                'label' => 'Type: ',
                'attr' => array(
                    'placeholder' => '... click \'n type',
                )
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Optional description: ',
                'attr' => array(
                    'rows' => 5,
                    'cols' => 40,
                ),
            ))
            ->add('categories', ChoiceType::class, array(
                'label' => 'Choose categories: ',
                'placeholder' => 'Choose categories',
                'multiple' => true,
                'choices' => $this->createCategoryChoices(),
                'choice_label' => function ($choice, $key, $value) {
                    return ucfirst($key);
                }
            ))
            ->add('image', ImageType::class, array(
                'label' => false,
            ))
            ->add('translations', CollectionType::class, array(
                'label' => 'Add translations ...',
                'entry_type' => TranslationType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ));

            $builder->get('categories')->addModelTransformer(new MultipleChoiceTransformer(
                (!$word->getCategories()->isEmpty()) ? $word->getCategories() : array(),
                $this->em->getRepository('AdminBundle:Category')
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

    private function createCategoryChoices()
    {
        $categories = $this->em->getRepository('AdminBundle:Category')->findAll();

        $choices = array();

        foreach ($categories as $category) {
            $choices[$category->getName()] = $category->getId();
        }

        return $choices;
    }
}