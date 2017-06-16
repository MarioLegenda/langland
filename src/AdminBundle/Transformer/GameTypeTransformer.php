<?php

namespace AdminBundle\Transformer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;

class GameTypeTransformer implements DataTransformerInterface
{
    private $em;
    /**
     * GameTypeTransformer constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param mixed $issue
     * @return array
     */
    public function transform($issue)
    {
        if (empty($issue)) {
            return null;
        }

        $gameTypes = array();
        foreach ($issue as $serviceName => $value) {
            $gameType = $this->em->getRepository('AdminBundle:GameType')->findOneBy(array(
                'serviceName' => $serviceName,
            ));

            $gameTypes[$gameType->getName()] = $gameType->getServiceName();
        }

        return $gameTypes;
    }
    /**
     * @param mixed $value
     * @return mixed
     */
    public function reverseTransform($value)
    {
        return $value;
    }
}