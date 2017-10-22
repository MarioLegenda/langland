<?php

namespace AdminBundle\Controller;

use Symfony\Component\DomCrawler\Crawler;
use TestLibrary\LanglandAdminTestCase;

class DashboardControllerTest extends LanglandAdminTestCase
{
    private $dashboardRoute = 'http://33.33.33.10/admin/dashboard';
    private $navTexts = [
        'Languages',
        'Language infos',
        'Categories',
        'Words',
        'Sounds',
        'Courses'
    ];

    public function testMainNavigation()
    {
        foreach ($this->navTexts as $text) {
            $this->doTestDashboard($this->dashboardRoute, $text);
        }
    }

    public function testDashboard()
    {
        $dashboard = $this->client->request('GET', $this->dashboardRoute);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $panels = $dashboard->filter('#page-wrapper')->filter('.panel-primary');

        $this->assertEquals(6, count($panels));

        $panels->each(function(Crawler $panel) {
            $links = $panel->filter('a')->links();

            foreach ($links as $link) {
                $this->client->click($link);

                $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
            }
        });
    }
}