<?php

namespace CommonBundle\Command;

use BlueDot\BlueDot;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TruncateDatabaseCommand extends ContainerAwareCommand
{
    /**
     * @void
     */
    public function configure()
    {
        $this
            ->setName('database:truncate');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var BlueDot $blueDot */
        $blueDot = $this->getContainer()->get('common.blue_dot');

        $blueDot->createStatementBuilder()->addSql('SET foreign_key_checks = 0')->execute();

        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE learning_game_data')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE learning_game_challenges')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE learning_games')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE learning_lessons')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE learning_metadata')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE learning_users')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE questions')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE language_info_texts')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE categories')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE data_collector')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE images')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE language_infos')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE languages')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE lessons')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE questions')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE roles')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE sounds')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE users')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE word_categories')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE word_translations')->execute();
        $blueDot->createStatementBuilder()->addSql('TRUNCATE TABLE words')->execute();

        $blueDot->createStatementBuilder()->addSql('SET foreign_key_checks = 1')->execute();
    }
}