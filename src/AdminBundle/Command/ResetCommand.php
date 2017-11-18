<?php

namespace AdminBundle\Command;

use ArmorBundle\Entity\Role;
use ArmorBundle\Entity\User;
use AdminBundle\Command\Helper\UserFactory;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * @void
     */
    public function configure()
    {
        $this
            ->setName('langland:reset')
            ->setDescription('Seeds users');
    }
    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();

        $this->resetDatabase();
        $this->createUsers();
    }
    /**
     * @void
     */
    private function resetDatabase()
    {
        exec('/usr/bin/php bin/console do:da:dr --force');
        exec('/usr/bin/php bin/console do:da:cr');
        exec('/usr/bin/php bin/console do:sc:up --force');
    }
    /**
     * @void
     */
    private function createUsers()
    {
        $userFactory = new UserFactory($this->em, $this->getContainer()->get('security.password_encoder'));

        $userFactory
            ->create('root', 'root', new Role('ROLE_ADMIN'))
            ->create('marioskrlec@outlook.com', 'root', new Role('ROLE_USER'));
    }
}