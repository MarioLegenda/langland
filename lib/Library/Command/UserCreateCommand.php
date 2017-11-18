<?php

namespace Library\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Webmozart\Assert\Assert;
use ArmorBundle\Entity\Role;

class UserCreateCommand extends Command
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     */
    protected function askForRoles(InputInterface $input, OutputInterface $output): array
    {
        $question = new Question('Roles (comma separated): ', null);
        $answer = $this->getHelper('question')->ask($input, $output, $question);

        Assert::string($answer, 'Roles should be a comma separated string');

        $roles = explode(',', $answer);

        $roleObjects = [];
        foreach ($roles as $role) {
            $roleObjects[] = new Role(trim($role));
        }

        return $roleObjects;
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    protected function askForName(InputInterface $input, OutputInterface $output): string
    {
        $question = new Question('Name: ', null);

        $answer = $this->getHelper('question')->ask($input, $output, $question);

        Assert::string($answer, 'Name should be a strig');

        return $answer;
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    protected function askForLastname(InputInterface $input, OutputInterface $output): string
    {
        $question = new Question('Last name: ', null);

        $answer = $this->getHelper('question')->ask($input, $output, $question);

        Assert::string($answer, 'Last name should be a strig');

        return $answer;
    }
}