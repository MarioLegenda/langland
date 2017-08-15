<?php

namespace Library\Infrastructure\FileUpload\Implementation;

use Doctrine\ORM\EntityManager;
use Library\Infrastructure\FileUpload\FileNamerInterface;

class FileNamer implements FileNamerInterface
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
    public function createName(array $options) : string
    {
        $validOptions = array('field', 'repository');

        $diff = array_diff($validOptions, array_keys($options));

        if (!empty($diff)) {
            throw new \RuntimeException(
                sprintf('FileNamer could not create a name. Invalid options. Valid options are %s', implode(', ', $validOptions))
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