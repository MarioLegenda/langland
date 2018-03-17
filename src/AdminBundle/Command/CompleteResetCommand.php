<?php

namespace AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CompleteResetCommand extends ContainerAwareCommand
{
    /**
     * @void
     */
    public function configure()
    {
        $this
            ->setName('langland:complete_reset --words=20 --lessons=100')
            ->addOption('words', 'w', InputOption::VALUE_OPTIONAL, null, 10)
            ->addOption('lessons', 'l', InputOption::VALUE_OPTIONAL, null, 5)
            ->setDescription('Executes langland:learning_metadata:reset and langland:learning_metadata:seed together');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->isValidEnvironment();

        $words = $input->getOption('words');
        $lessons = $input->getOption('lessons');

        $seedCommand = sprintf(
            '/usr/bin/php bin/console langland:learning_metadata:seed --words=%s --lessons=%s',
            $words,
            $lessons
        );

        exec('/usr/bin/php bin/console langland:learning_metadata:reset');
        exec($seedCommand);
    }
    /**
     * @throws \RuntimeException
     */
    private function isValidEnvironment()
    {
        $env = $this->getContainer()->get('kernel')->getEnvironment();
        $validEnvironments = ['dev', 'test'];

        if (!in_array($env, $validEnvironments)) {
            $message = sprintf('This command can only be executed in \'%s\' environments', implode(', ', $validEnvironments));

            throw new \RuntimeException($message);
        }
    }
}