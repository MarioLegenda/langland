<?php

namespace Library\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use BlueDot\BlueDotInterface;
use ArmorBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Test\BlueDot\Model\Language;

class BlueDotTestCase extends WebTestCase
{
    /**
     * @var BlueDotInterface
     */
    protected $blueDot;
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $faker;

    public function setUp()
    {
        $client = self::createClient();
        $this->container = $client->getContainer();
        $this->faker = \Faker\Factory::create();

        $this->blueDot = $this->container->get('app.blue_dot');

        $this->blueDot->useApi('seed');
        $this->blueDot->execute('scenario.seed');

        $this->blueDot->useApi('user');
        $this->createAdminUser($this->blueDot, 'root', 'root');
        $this->createRegularUser($this->blueDot, 'mile', 'root');

        $languageRepository = $this->container->get('api.shared.language_repository');

        $languages = array(
            'french',
            'spanish',
            'italian',
        );

        foreach ($languages as $language) {
            $languageRepository->create(array(
                'language' => $language
            ));
        }

        $languages = array(
            'swedish',
            'norwegian',
            'bosnian',
        );

        $objectLanguages = array();

        foreach ($languages as $language) {
            $l = new Language();
            $l->setLanguage($language);

            $objectLanguages[] = $l;
        }

        $this->blueDot->execute('simple.insert.create_language', $objectLanguages);

        $this->blueDot->execute('simple.insert.create_language', array(
            array('language' => 'japanese'),
            array('language' => 'belgium'),
        ));

        $languageRepository->updateWorkingLanguage(array(
            'user_id' => 1,
            'language_id' => 1,
        ));

        $categories = array('nature', 'space', 'kitchen', 'room', 'body');

        $this->blueDot->useApi('category');

        $this->blueDot->execute('scenario.create_category', array(
            'create_category' => array(
                'category' => $categories,
            ),
        ));

        $this->blueDot->useApi('words');

        $this->blueDot->execute('scenario.create_word', array(
            'find_working_language' => array(
                'user_id' => 1,
            ),
            'create_word' => array(
                'word' => 'andrea',
                'type' => 'curly',
            ),
            'create_image' => array(
                'relative_path' => 'relative_path',
                'absolute_path' => 'absolute_path',
                'file_name' => 'file_name',
                'absolute_full_path' => 'absolute_full_path',
                'relative_full_path' => 'relative_full_path',
                'original_name' => 'original name'
            ),
            'create_word_categories' => array(
                'category_id' => array(1, 2, 3),
            ),
            'create_translations' => array(
                'translation' => array(
                    'translation 1',
                    'translation 2',
                    'translation 3',
                    'translation 4',
                ),
            ),
        ));

        $this->blueDot->execute('scenario.create_word', array(
            'find_working_language' => array(
                'user_id' => 1,
            ),
            'create_word' => array(
                'word' => 'deja',
                'type' => 'curly',
            ),
            'create_image' => array(
                'relative_path' => 'relative_path',
                'absolute_path' => 'absolute_path',
                'file_name' => 'file_name',
                'absolute_full_path' => 'absolute_full_path',
                'relative_full_path' => 'relative_full_path',
                'original_name' => 'original name',
            ),
            'create_word_categories' => array(
                'category_id' => array(3, 4, 1),
            ),
            'create_translations' => array(
                'translation' => array(
                    'translation 1',
                    'translation 2',
                ),
            ),
        ));

        $this->blueDot->execute('scenario.create_word', array(
            'find_working_language' => array(
                'user_id' => 1,
            ),
            'create_word' => array(
                'word' => 'kovrcava',
                'type' => 'curly',
            ),
            'create_image' => array(
                'relative_path' => 'relative_path',
                'absolute_path' => 'absolute_path',
                'file_name' => 'file_name',
                'absolute_full_path' => 'absolute_full_path',
                'relative_full_path' => 'relative_full_path',
                'original_name' => 'original name',
            ),
            'create_word_categories' => array(
                'category_id' => array(1, 5),
            ),
            'create_translations' => array(
                'translation' => array(
                    'translation 1',
                    'translation 2',
                    'translation 3',
                    'translation 4',
                    'translation 5',
                    'translation 6',
                    'translation 7',
                    'translation 8',
                ),
            ),
        ));
    }

    private function createAdminUser(BlueDotInterface $blueDot, string $username, string $password)
    {
        $encoder = $this->container->get('security.password_encoder');

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
        $encoder = $this->container->get('security.password_encoder');

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