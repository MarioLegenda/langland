<?php

namespace Library\App;

use Doctrine\ORM\EntityManager;

class FileNamer
{
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * FileNamer constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param array $options
     * @return null|string
     */
    public function createName(array $options)
    {
        $validOptions = array('field', 'repository');

        $diff = array_diff($validOptions, array_keys($options));

        if (!empty($diff)) {
            throw new \RuntimeException(
                sprintf('FileNamer could not create name. Invalid options')
            );
        }

        $repo = $this->em->getRepository($options['repository']);
        $name = null;

        for(;;) {
            $name = $this->doCreateName($repo, $options['field']);

            if (is_string($name)) {
                break;
            }
        }

        return $name;
    }

    private function doCreateName($repo, $field)
    {
        $name = md5(uniqid());

        $result = $repo->findBy(array(
            $field => $name,
        ));

        if (empty($result)) {
            return $name;
        }

        return null;
    }
}