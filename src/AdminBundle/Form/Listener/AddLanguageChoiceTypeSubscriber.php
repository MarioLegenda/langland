<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 03.06.17.
 * Time: 09:51
 */

namespace AdminBundle\Form\Listener;


use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AdminBundle\Transformer\SingleChoiceTransformer;

class AddLanguageChoiceTypeSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * @var object $entity
     */
    private $entity;
    /**
     * AddTranslationCollectionSubscriber constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, $entity)
    {
        $this->em = $em;
        $this->entity = $entity;
    }
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        );
    }

    public function onPreSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        $form
            ->add('language', ChoiceType::class, array(
                'label' => 'Language: ',
                'placeholder' => 'Choose language',
                'choices' => $this->createLanguageChoices($this->em),
                'choice_label' => function($choice, $key, $value) {
                    return ucfirst($key);
                }
            ));

        $form->get('language')->addModelTransformer(new SingleChoiceTransformer(
            ($this->entity->getLanguage()) instanceof Language ? $this->entity->getLanguage()->getId() : null,
            $this->em->getRepository('AdminBundle:Language')
        ));
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