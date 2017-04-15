<?php

namespace AdminBundle\Command;

use AdminBundle\Entity\Category;
use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use BlueDot\BlueDotInterface;
use BlueDot\Entity\PromiseInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require_once __DIR__.'/../../../vendor/fzaninotto/faker/src/autoload.php';

class SeedCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('langland:seed')
            ->setDescription('Seeds initial data');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $blueDot = $this->getContainer()->get('app.blue_dot');

        $blueDot->setConfiguration(__DIR__.'/blue_dot.yml');

        $faker = \Faker\Factory::create();

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

        $blueDot->execute('scenario.database');
        $blueDot->execute('scenario.seed');

        $this->createAdminUser($blueDot, 'root', 'root');
        $this->createAdminUser($blueDot, 'mile', 'root');
        $this->createRegularUser($blueDot);

        $output->writeln('<info>Finished creating database and users</info>');
        $output->writeln('');

        $languages = array(
            'croatian',
            'english',
            'french',
            'spanish',
            'german',
            'italian',
        );

        $languageModels = array();

        foreach ($languages as $language) {
            $languageModels[] = (new Language())->setLanguage($language);
        }

        $categories = array(
            'nature',
            'house',
            'road',
            'city',
            'construction',
            'programming',
            'medicine',
            'history',
            'hardware',
            'software',
        );


        $output->writeln('<info>SEEDING STARTED</info>');
        $output->writeln('');

        $inserts = 0;
        $start = time();
        foreach ($languageModels as $languageModel) {
            $languageId = $blueDot->execute('simple.insert.create_language', $languageModel)
                ->success(function(PromiseInterface $promise) {
                    return $promise->getResult()->get('last_insert_id');
                })->getResult();

            $inserts++;

            $blueDot
                ->createStatementBuilder()
                ->addSql(sprintf('INSERT INTO courses (language_id, name) VALUES (%d, "%s")', $languageId, sprintf('%s course', ucfirst($languageModel->getLanguage()))))
                ->execute()
                ->success(function(PromiseInterface $promise) use ($blueDot, $languageModel) {
                    $courseId = $promise->getResult()->get('last_insert_id');

                    $blueDot->execute('simple.insert.create_class', array(
                        'course_id' => $courseId,
                        'name' => sprintf('Gentle %s introduction', ucfirst($languageModel->getLanguage())),
                    ));
                });

            $inserts++;

            $output->writeln(sprintf('<info>Currently added language: %s</info>', $languageModel->getLanguage()));

            for ($a = 0; $a < 10; $a++) {
                $category = new Category();
                $category->setCategory($categories[$a]);
                $category->setLanguageId($languageId);

                $categoryId = $blueDot->execute('simple.insert.create_category', $category)
                ->success(function(PromiseInterface $promise) {
                    return $promise->getResult()->get('last_insert_id');
                })->getResult();

                $inserts++;

                for ($i = 0; $i < 10; $i++) {
                    $blueDot->execute('scenario.insert_word', array(
                        'insert_word' => array(
                            'language_id' => $languageId,
                            'word' => $faker->word,
                            'type' => $faker->company,
                        ),
                        'insert_word_image' => array(
                            'relative_path' => 'relative_path',
                            'absolute_path' => 'absolute_path',
                            'file_name' => 'file_name',
                            'absolute_full_path' => 'absolute_full_path',
                            'relative_full_path' => 'relative_full_path',
                        ),
                       'insert_translation' => array(
                            'translation' => $faker->words(rand(1, 25)),
                        ),
                        'insert_word_category' => array(
                            'category_id' => $categoryId,
                        ),
                    ))->success(function(PromiseInterface $promise) use (&$inserts) {
                        $translationRowCount = $promise->getResult()->get('insert_translation')->get('row_count');
                        $insertWordRowCount = $promise->getResult()->get('insert_word')->get('row_count');
                        $insertCategoryRowCount = $promise->getResult()->get('insert_word_category')->get('row_count');

                        $inserts += (int) $translationRowCount;
                        $inserts += (int) $insertWordRowCount;
                        $inserts += (int) $insertCategoryRowCount;
                    });
                }
            }
        }

        $finish = time() - $start;

        $inserts++;

        $blueDot->execute('simple.update.update_working_language', array(
            'working_language' => 1,
            'id' => 1,
        ));

        $output->writeln('<info>Clearing symfony cache</info>');
        $output->writeln('');

        exec('/usr/bin/php /var/www/app/console cache:clear --env=dev');

        $output->writeln('<info>Cached cleared</info>');

        $output->writeln('');
        $output->writeln('<info>SEED FINISHED</info>');
        $output->writeln('');

        $output->writeln(sprintf('<info>Total of %d sql statements executed in %d seconds</info>', $inserts, $finish));
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
