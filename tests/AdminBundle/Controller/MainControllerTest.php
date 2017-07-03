<?php

namespace AdminBundle\Controller;


use Symfony\Component\DomCrawler\Crawler;
use TestLibrary\LanglandAdminTestCase;

class MainControllerTest extends LanglandAdminTestCase
{
    private $linkTexts = array(
        'Languages',
        'Language info',
        'Categories',
        'Words',
        'Sounds',
        'Courses',
        'Logout',
    );

    private $route = '/admin/dashboard';

    public function testDashboard()
    {
        $crawler = $this->clientGet($this->route);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $mainDashboardLink = $crawler->selectLink('Langland - admin')->link();

        $this->client->click($mainDashboardLink);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $links = $crawler->filter('.nav-link');

        $links->each(function(Crawler $link) {
            $linkText = $link->text();

            $this->assertContains($linkText, $this->linkTexts);

            $clickableLink = $link->selectLink($linkText)->link();

            $this->client->click($clickableLink);

            if ($linkText === 'Logout') {
                $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
            } else {
                $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
            }
        });
    }
}