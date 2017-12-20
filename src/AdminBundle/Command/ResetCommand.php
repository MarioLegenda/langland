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

        $this->resetDatabase($output);
        $this->clearCache($output);
        $this->createUsers($output);

        $output->writeln('');
        $output->writeln('<info>Command finished</info>');
    }
    /**
     * @param OutputInterface $output
     */
    private function resetDatabase(OutputInterface $output): void
    {
        $output->writeln('<info>Resetting database</info>');

        exec('/usr/bin/php bin/console do:da:dr --force');
        exec('/usr/bin/php bin/console do:da:cr');
        exec('/usr/bin/php bin/console do:sc:up --force');

        $output->writeln('<info>Database reset finished</info>');
    }
    /**
     * @param OutputInterface $output
     */
    private function clearCache(OutputInterface $output): void
    {
        $output->writeln('<info>Clearing cache for dev environment</info>');
        exec('/usr/bin/php bin/console cache:clear --env=dev');
        $output->writeln('<info>Cache cleared</info>');

        $output->writeln('<info>Warming up cache for dev environment</info>');
        exec('/usr/bin/php bin/console cache:warmup --env=dev');
        $output->writeln('<info>Cache warming finished</info>');
    }
    /**
     * @param OutputInterface $output
     */
    private function createUsers(OutputInterface $output)
    {
        $output->writeln('<info>Creating admin and public users</info>');

        $userFactory = new UserFactory($this->em, $this->getContainer()->get('security.password_encoder'));

        $userFactory
            ->create('root', 'root', [new Role('ROLE_ADMIN')])
            ->create('marioskrlec@outlook.com', 'root', [
                new Role('ROLE_PUBLIC_API_USER'),
                new Role('ROLE_USER'),
            ]);

        $output->writeln('<info>Users created</info>');
    }
}