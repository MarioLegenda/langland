<?php

namespace CommonBundle\Command;

use ArmorBundle\Entity\User;
use ArmorBundle\Repository\UserRepository;
use PublicApi\Infrastructure\Communication\RepositoryCommunicator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QueryTestingCommand extends ContainerAwareCommand
{
    /**
     * @var RepositoryCommunicator $repositoryCommunicator
     */
    private $repositoryCommunicator;
    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this
            ->setName('common:debug:query');
    }
    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->repositoryCommunicator = $this->getContainer()->get('public_api.repository.repository_communicator');
        $this->userRepository = $this->getContainer()->get('armor.repository.user');

        $this->getAllAlreadyLearningLanguages();
    }

    private function getAllAlreadyLearningLanguages()
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy([
            'username' => 'marioskrlec@outlook.com',
        ]);

        $languages = $this->repositoryCommunicator->getAllAlreadyLearningLanguages($user);

        dump($languages);
        die();
    }
}