<?php

namespace AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ArmorBundle\Entity\User;
use BlueDot\BlueDotInterface;

class EmptyCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('langland:empty')
            ->setDescription('Seeds initial data');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $blueDot = $this->getContainer()->get('app.blue_dot');

        $output->writeln('');
        $output->writeln('<info>Script started</info>');
        $output->writeln('');

        $output->writeln('<info>Configuring files</info>');

        $dirs = array('/var/www/web/sounds', '/var/www/web/uploads');

        foreach ($dirs as $dir) {
            if (is_dir($dir)) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::CHILD_FIRST
                );

                foreach ($files as $fileinfo) {
                    $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                    $todo($fileinfo->getRealPath());
                }

                rmdir($dir);
            }
        }

        $twigFilesDir = $this->getContainer()->getParameter('deck_files_dir');

        if (is_dir($twigFilesDir)) {
            $twigFiles = scandir($twigFilesDir);

            foreach ($twigFiles as $twigFile) {
                if ($twigFile !== '.' and $twigFile !== '..') {
                    unlink($twigFilesDir.'/'.$twigFile);
                }
            }

            rmdir($this->getContainer()->getParameter('deck_files_dir'));
        }

        mkdir($this->getContainer()->getParameter('deck_files_dir'));

        mkdir('/var/www/web/sounds');
        mkdir('/var/www/web/sounds/temp');
        mkdir('/var/www/web/uploads');

        $output->writeln('<info>Files configured successfully</info>');
        $output->writeln('');

        $output->writeln('<info>Creating database and initial users</info>');

        $blueDot->useApi('seed');
        $blueDot->execute('scenario.seed');

        $blueDot->useApi('user');
        $this->createAdminUser($blueDot, 'root', 'root');
        $this->createAdminUser($blueDot, 'mile', 'root');
        $this->createRegularUser($blueDot);

        $output->writeln('<info>Finished creating database and users</info>');
        $output->writeln('');
    }


    private function createAdminUser(BlueDotInterface $blueDot, string $username, string $password)
    {
        $encoder = $this->getContainer()->get('security.password_encoder');

        $userData = array(
            'name' => 'Mile',
            'lastname' => 'Milozvučić',
            'username' => $username,
            'password' => $password,
            'gender' => 'male',
            'enabled' => 1,
            'roles' => serialize(array('ROLE_DEVELOPER')),
        );

        $user = new User($userData);

        $user->setPassword($encoder->encodePassword($user, 'root'));

        $blueDot->execute('simple.insert.create_user', $user);
    }

    private function createRegularUser(BlueDotInterface $blueDot)
    {
        $encoder = $this->getContainer()->get('security.password_encoder');

        $userData = array(
            'name' => 'Mile',
            'lastname' => 'Milozvučić',
            'username' => 'mile',
            'password' => 'root',
            'gender' => 'female',
            'enabled' => 1,
            'roles' => serialize(array('ROLE_USER')),
        );

        $user = new User($userData);

        $user->setPassword($encoder->encodePassword($user, 'root'));

        $blueDot->execute('simple.insert.create_user', $user);
    }
}