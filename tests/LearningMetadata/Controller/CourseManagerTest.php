<?php

namespace AdminBundle\Controller;

use AdminBundle\Command\Helper\CourseFactory;
use AdminBundle\Command\Helper\LanguageFactory;
use AdminBundle\Entity\Course;
use AdminBundle\Entity\Language;
use Symfony\Component\DomCrawler\Crawler;
use TestLibrary\LanglandAdminTestCase;

class CourseManagerTest extends LanglandAdminTestCase
{
    private $navText = 'Courses';
    private $dashboardRoute = 'http://33.33.33.10/admin/dashboard';

    private $courseNames = [];
    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $em = $this->container->get('doctrine')->getManager();
        $languageFactory = new LanguageFactory($em);

        $languages = $languageFactory->create([
            'French',
            'English',
        ], true);

        $courseFactory = new CourseFactory($em);
        /** @var Language $language */
        foreach ($languages as $language) {
            $courses = $courseFactory->create($language, 5);

            /** @var Course $course */
            foreach ($courses as $course) {
                $this->courseNames[] = $course->getName();
            }
        }
    }

    public function test_course_manager()
    {
        $indexCrawler = $this->doTestIndex(
            $this->dashboardRoute,
            $this->navText,
            $this->courseNames
        );

        $courseRows = $indexCrawler->filter('tbody tr');

        $lastLink = null;
        $courseRows->each(function(Crawler $row) use (&$lastLink) {
            $lastLink = $row->selectLink('Manage')->link();

            $this->client->click($lastLink);

            static::assertEquals(200, $this->client->getResponse()->getStatusCode());
        });

        static::assertInternalType('object', $lastLink);

        $courseManagerCrawler = $this->client->click($lastLink);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}