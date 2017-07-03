<?php

namespace AdminBundle\Controller;

use TestLibrary\LanglandAdminTestCase;
use FilesystemIterator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SoundControllerTest extends LanglandAdminTestCase
{
    private $navText = 'Sounds';
    private $dashboardRoute = '/admin/dashboard';
    private $createUri = 'http://33.33.33.10/admin/sound/create';

    public function testCreate()
    {
        $createCrawler = $this->client->click($this->doTestDashboard($this->dashboardRoute, $this->navText)->selectLink('Create')->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($this->createUri, $this->client->getRequest()->getUri());

        $form = $createCrawler->selectButton('Create')->form();

        $form['form[soundFile][0]']->upload(__DIR__.'/testSounds/test.mp3');

        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $editedFi = new FilesystemIterator(__DIR__.'/../../uploads/sounds', FilesystemIterator::SKIP_DOTS);

        $this->assertEquals(1, iterator_count($editedFi));
    }

    public function testIndex()
    {
        $this->doTestIndex(
            $this->dashboardRoute,
            $this->navText,
            array('test.mp3')
        );
    }
}