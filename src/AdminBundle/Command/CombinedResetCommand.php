<?php

namespace AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CombinedResetCommand extends ContainerAwareCommand
{
    /**
     * @void
     */
    public function configure()
    {
        $this
            ->setName('langland:complete_reset');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        exec('/usr/bin/php bin/console langland:reset');
        exec('/usr/bin/php bin/console langland:seed');
    }
}