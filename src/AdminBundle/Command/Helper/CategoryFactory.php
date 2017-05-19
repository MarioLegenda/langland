<?php

namespace AdminBundle\Command\Helper;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use AdminBundle\Entity\Category;

class CategoryFactory
{
    /**
     * @var array $categoryObjects
     */
    private $categoryObjects;
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * CategoryFactory constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param array $categories
     * @param bool $save
     * @return array
     */
    public function create(array $categories, bool $save = false) : array
    {
        $categoryObjects = array();

        foreach ($categories as $cat) {
            $category = new Category();
            $category->setName($cat);

            $categoryObjects[] = $category;

            $this->em->persist($category);
        }

        if ($save) {
            $this->categoryObjects = $categoryObjects;
        }

        return $categoryObjects;
    }
    /**
     * @param int $numberOfEntries
     * @return ArrayCollection
     */
    public function createCollection(int $numberOfEntries) : ArrayCollection
    {
        if (empty($this->categoryObjects)) {
            throw new \RuntimeException('CategoryFactory::createCollection: Categories have not been create with save option');
        }

        if ($numberOfEntries > count($this->categoryObjects)) {
            throw new \RuntimeException('CategoryFactory::createCollection: numberOfEntries has to be less or equal to number of created categories');
        }

        $alreadySelected = array();

        $categoryCollection = new ArrayCollection();
        for (;;) {
            if ($numberOfEntries === count($alreadySelected)) {
                break;
            }

            $entry = rand(0, $numberOfEntries);

            if (in_array($entry, $alreadySelected) === true) {
                continue;
            }

            $category = $this->categoryObjects[$entry];

            $categoryCollection->add($category);

            $alreadySelected[] = $entry;
        }

        return $categoryCollection;
    }
}