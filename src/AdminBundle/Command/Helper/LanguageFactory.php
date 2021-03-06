<?php

namespace AdminBundle\Command\Helper;

use AdminBundle\Entity\Image;
use Doctrine\ORM\EntityManager;
use AdminBundle\Entity\Language;

class LanguageFactory
{
    use FakerTrait;
    /**
     * @var array $languageObjects
     */
    private $languageObjects;
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * LanguageFactory constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param array $languages
     * @param bool $save
     * @return array
     */
    public function create(array $languages, bool $save = false) : array
    {
        $languageObjects = array();
        foreach ($languages as $lang) {
            $language = new Language();
            $language->setName($lang);
            $language->setShowOnPage(true);
            $language->setListDescription($this->getFaker()->sentence(30));
            $language->setImages($this->createImages());

            $this->em->persist($language);

            $languageObjects[] = $language;
        }

        if ($save) {
            $this->languageObjects = $languageObjects;
        }

        $this->em->flush();

        return $languageObjects;
    }
    /**
     * @return array
     */
    private function createImages(): array
    {
        $images = [
            'icon' => 'icon.png',
            'cover_image' => 'cover.jpg',
        ];

        $objects = [];

        foreach ($images as $name => $image) {
            $object = new Image();
            $object->setName($image);
            $object->setRelativePath('/images/french');
            $object->setOriginalName($image);
            $object->setFullPath('/var/www/web/images/french/'.$image);
            $object->setTargetDir('/var/www/web/uploads/images');

            $objects[$name] = $object->toArray();
        }

        return $objects;
    }
}