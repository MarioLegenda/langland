<?php

namespace AdminBundle\Form\Type;

use API\SharedDataBundle\Repository\CategoryRepository;
use API\SharedDataBundle\Repository\Status;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Length;
use AdminBundle\Validator\Constraint\WordExistsConstraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class WordType extends AbstractType
{
    /**
     * @var CategoryRepository $categoryRepo
     */
    private $categoryRepo;
    /**
     * WordType constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepo = $categoryRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('word', TextType::class, array(
                'label' => 'Word: ',
                'attr' => array(
                    'placeholder' => '... click \'n type',
                ),
                'constraints' => array(
                    new NotNull(array(
                        'message' => 'Word name cannot be blank'
                    )),
                    new Length(array(
                        'max' => 50,
                        'maxMessage' => 'Word name can have up to {{ limit }} characters',
                    )),
                    new WordExistsConstraint(),
                )
            ))
            ->add('type', TextType::class, array(
                'label' => 'Type: ',
                'attr' => array(
                    'placeholder' => '... click \'n type',
                ),
                'constraints' => array(
                    new NotNull(array(
                        'message' => 'Word type cannot be blank'
                    )),
                    new Length(array(
                        'max' => 50,
                        'maxMessage' => 'Word type can have up to {{ limit }} characters',
                    )),
                    new WordExistsConstraint(),
                )
            ))
            ->add('category', ChoiceType::class, array(
                'label' => 'Choose category: ',
                'placeholder' => 'Choose a category',
                'multiple' => true,
                'choices' => $this->createCategoryChoices(),
            ))
            ->add('image', FileType::class, array(
                'label' => 'Associate an image with this word',
            ))
            ->add('translations', CollectionType::class, array(
                'label' => 'Translations: ',
                'entry_type' => TranslationType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'constraints' => array(
                    new Callback(function(array $translations, ExecutionContextInterface $context) {
                        foreach ($translations as $translation) {
                            if (is_string($translation['translation'])) {
                                return;
                            }
                        }

                        $context->buildViolation('You have to provide at least one translation')
                            ->atPath('translations')
                            ->addViolation();
                    }),
                )
            ));
    }
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'form';
    }

    private function createCategoryChoices()
    {
        $resultResolver = $this->categoryRepo->findAllForWorkingLanguage();

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return array();
        }

        $categories = $resultResolver->getData();

        $choices = array();
        foreach ($categories as $category) {
            $choices[$category['category']] = $category['id'];
        }

        return $choices;
    }
}