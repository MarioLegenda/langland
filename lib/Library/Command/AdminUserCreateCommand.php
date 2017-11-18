<?php

namespace Library\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ArmorBundle\Entity\User;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use ArmorBundle\Entity\Role;
use Webmozart\Assert\Assert;

class AdminUserCreateCommand extends UserCreateCommand
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;
    /**
     * @var UserPasswordEncoder $encoder
     */
    private $encoder;
    /**
     * AdminUserCreateCommand constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoder $encoder
     */
    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoder $encoder
    ) {
        $this->em = $em;
        $this->encoder = $encoder;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('langland:admin:create');
    }
    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->getData($input, $output);

        if (!empty($data)) {
            $user = $this->createUser($data);

            $this->em->persist($user);
            $this->em->flush();

            $output->writeln('');
            $output->writeln(sprintf('<info>User \'%s\' has been created</info>', $user->getUsername()));
            $output->writeln('');
        }
    }

    private function createUser(array $data): User
    {
        $user = new User();
        $user->setUsername($data['username']);
        $password = $this->encoder->encodePassword($user, $data['password']);
        $user->setPassword($password);
        $user->setName($data['name']);
        $user->setLastname($data['lastname']);
        $user->setGender($data['gender']);

        /** @var Role $role */
        foreach ($data['roles'] as $role) {
            $user->addRole($role);
        }

        $user->setEnabled(true);

        return $user;
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     */
    private function getData(InputInterface $input, OutputInterface $output): array
    {
        try {
            return [
                'name' => $this->askForName($input, $output),
                'lastname' => $this->askForLastname($input, $output),
                'gender' => $this->askForGender($input, $output),
                'username' => $this->askForUsername($input, $output),
                'password' => $this->askForPassword($input, $output),
                'roles' => $this->askForRoles($input, $output)
            ];
        } catch (\Exception $e) {
            $output->writeln('');
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            $output->writeln('');

            return [];
        }
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    public function askForGender(InputInterface $input, OutputInterface $output): string
    {
        $question = new ChoiceQuestion('Gender', [
            'male',
            'female',
        ]);

        $answer = $this->getHelper('question')->ask($input, $output, $question);

        return $answer;
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    public function askForUsername(InputInterface $input, OutputInterface $output): string
    {
        $question = new Question('Username: ', null);

        $answer = $this->getHelper('question')->ask($input, $output, $question);

        Assert::string($answer, 'Username should be a strig');

        return $answer;
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    public function askForPassword(InputInterface $input, OutputInterface $output): string
    {
        $question = new Question('Password: ', null);
        $question->setHidden(true);
        $question->setHiddenFallback(false);

        $answer = $this->getHelper('question')->ask($input, $output, $question);

        Assert::string($answer, 'Password should be a strig');

        return $answer;
    }
}