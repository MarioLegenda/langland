<?php

namespace AdminBundle\Command;

use ArmorBundle\Entity\Role;
use ArmorBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('langland:reset')
            ->setDescription('Seeds users');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        exec('/usr/bin/php bin/console do:da:dr --force');
        exec('/usr/bin/php bin/console do:da:cr');
        exec('/usr/bin/php bin/console do:sc:up --force');

        $this->createAdminUser('root', 'root');
        $this->createRegularUser('mile', 'root');
    }

    private function createAdminUser(string $username, string $password)
    {
        $user = new User();
        $user->setUsername($username);
        $password = $this->getContainer()->get('security.password_encoder')
            ->encodePassword($user, $password);
        $user->setPassword($password);
        $user->setName('Mile');
        $user->setLastname('Mile');
        $user->setGender('male');

        $role = new Role();
        $role->setRole('ROLE_DEVELOPER');
        $user->addRole($role);

        $user->setEnabled(true);

        $em = $this->getContainer()->get('doctrine')->getManager();

        $em->persist($user);
        $em->flush();
    }

    public function createRegularUser(string $username, string $password)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setName('Mile');
        $user->setLastname('Mile');
        $user->setGender('male');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_USER'));

        $role = new Role();
        $role->setRole('ROLE_USER');
        $user->addRole($role);
        $em = $this->getContainer()->get('doctrine')->getManager();

        $em->persist($user);
        $em->flush();
    }
}