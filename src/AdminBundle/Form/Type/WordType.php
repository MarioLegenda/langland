<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\Category;
use AdminBundle\Entity\Language;
use AdminBundle\Repository\LanguageRepository;
use AdminBundle\Transformer\CollectionNullableTransformer;
use AdminBundle\Transformer\MultipleChoiceTransformer;
use AdminBundle\Transformer\SingleChoiceTransformer;
use AdminBundle\Validator\Constraint\UniqueConstraint;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AdminBundle\Entity\Word;

class WordType extends AbstractType
{
    /**
     * @var \Doctrine\ORM\EntityRepository
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

        $builder
            ->add('language', ChoiceType::class, array(
                    'label' => 'Language: ',
                    'placeholder' => 'Choose language',
                    'choices' => $this->createLanguageChoices(),
            ))
            ->add('name', TextType::class, array(
                'label' => 'Word: ',
                'attr' => array(
                    'placeholder' => '... click \'n type',
                ),
            ))
            ->add('type', TextType::class, array(
                'label' => 'Type: ',
                'attr' => array(
                    'placeholder' => '... click \'n type',
                )
            ))
            ->add('categories', ChoiceType::class, array(
                    'label' => 'Choose categories: ',
                    'placeholder' => 'Choose categories',
                    'multiple' => true,
                    'choices' => $this->createCategoryChoices(),
            ))
            ->add('wordImage', WordImageType::class, array(
                'label' => false,
            ));

            $builder->get('language')->addModelTransformer(new SingleChoiceTransformer(
                ($word->getLanguage()) instanceof Language ? $word->getLanguage()->getId() : null,
                $this->em->getRepository('AdminBundle:Language')
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

    private function createLanguageChoices()
    {
        $languages = $this->em->getRepository('AdminBundle:Language')->findAll();

        $choices = array();

        foreach ($languages as $language) {
            $choices[$language->getName()] = $language->getId();
        }

        return $choices;
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