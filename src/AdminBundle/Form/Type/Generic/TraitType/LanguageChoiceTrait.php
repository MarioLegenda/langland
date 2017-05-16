<?php

namespace AdminBundle\Form\Type\Generic\TraitType;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AdminBundle\Transformer\SingleChoiceTransformer;
use AdminBundle\Entity\Language;

trait LanguageChoiceTrait
{
    /**
     * @param FormBuilderInterface $builder
     * @param EntityManager $em
     * @param $entity
     *
     * @return LanguageChoiceTrait
     */
    public function addLanguageChoice(
        FormBuilderInterface $builder,
        EntityManager $em,
        $entity)
    {
        $builder
            ->add('language', ChoiceType::class, array(
                'label' => 'Language: ',
                'placeholder' => 'Choose language',
                'choices' => $this->createLanguageChoices($em),
                'choice_label' => function($choice, $key, $value) {
                    return ucfirst($key);
                }
            ));

        $builder->get('language')->addModelTransformer(new SingleChoiceTransformer(
            ($entity->getLanguage()) instanceof Language ? $entity->getLanguage()->getId() : null,
            $em->getRepository('AdminBundle:Language')
        ));

        return $this;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param EntityManager $em
     * @param $entity
     * @return FormBuilderInterface
     */
    public function createLanguageChoice(
        FormBuilderInterface $builder,
        EntityManager $em,
        $entity
    ) : FormBuilderInterface {
        $languageType = $builder
            ->create('language', ChoiceType::class, array(
                'label' => 'Language: ',
                'placeholder' => 'Choose language',
                'choices' => $this->createLanguageChoices($em),
                'choice_label' => function($choice, $key, $value) {
                    return ucfirst($key);
                }
            ));

        $languageType->addModelTransformer(new SingleChoiceTransformer(
            ($entity->getLanguage()) instanceof Language ? $entity->getLanguage()->getId() : null,
            $em->getRepository('AdminBundle:Language')
        ));

        return $languageType;
    }

    private function createLanguageChoices(EntityManager $em)
    {
        $languages = $em->getRepository('AdminBundle:Language')->findAll();

        $choices = array();

        foreach ($languages as $language) {
            $choices[$language->getName()] = $language->getId();
        }

        return $choices;
    }
}