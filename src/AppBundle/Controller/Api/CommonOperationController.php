<?php

namespace AppBundle\Controller\Api;

use Library\ResponseController;
use AppBundle\Entity\LearningUser;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class CommonOperationController extends ResponseController
{
    public function getLearningUser()
    {
        $learningUser = $this->getRepository('AppBundle:LearningUser')->findOneBy(array(
            'user' => $this->getUser(),
        ));

        if (!$learningUser instanceof LearningUser) {
            return null;
        }

        return $learningUser;
    }
    /**
     * @param string $repository
     * @return EntityRepository
     */
    protected function getRepository(string $repository) : EntityRepository
    {
        return $this->get('doctrine')->getRepository($repository);
    }
    /**
     * @return EntityManager
     */
    protected function getManager() : EntityManager
    {
        return $this->get('doctrine')->getManager();
    }
    /**
     * @param $data
     * @return CommonOperationController
     */
    protected function save($data) : CommonOperationController
    {
        if (is_object($data)) {
            $this->doSave($data);
        } else if (is_array($data)) {
            foreach ($data as $entity) {
                $this->doSave($entity);
            }
        }

        return $this;
    }

    private function doSave($entity)
    {
        $this->getManager()->persist($entity);
        $this->getManager()->flush();
    }
}