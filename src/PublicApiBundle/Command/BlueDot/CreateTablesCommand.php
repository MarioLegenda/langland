<?php

namespace PublicApiBundle\Command\BlueDot;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTablesCommand extends ContainerAwareCommand
{
    /**
     * @void
     */
    public function configure()
    {
        $this
            ->setName('langland:blue_dot:public_api:table_create');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $blueDot = $this->getContainer()->get('common.blue_dot');

        $blueDot->repository()->putRepository(__DIR__.'/create_tables.yml');
        $blueDot->useRepository('create_tables');

        $blueDot->execute('scenario.create_tables');
    }
}