<?php

namespace Library\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Goutte\Client as GouetteClient;

abstract class LanglandTestCase extends WebTestCase
{
    /**
     * @var string $host
     */
    private $host = 'http://33.33.33.10/web';
    /**
     * @var string $environment
     */
    private $environment = 'app_dev.php';
    /**
     * @var array $routes
     */
    private $routes = array();
    /**
     * @var GouetteClient
     */
    protected $client = null;

    public function setUp()
    {
        $this->client = new GouetteClient();
        $this->client->followRedirects(true);
        $this->client->setHeader('X-Requested-With', 'XMLHttpRequest');

        $this->logIn('root', 'root');
    }

    protected function getData(string $route, array $params = array()) : array
    {
        $url = $this->getRoute($route);

        $this->client->request('POST', $url, $params);

        $response = $this->client->getResponse();

        return array(
            'data' => json_decode($response->getContent(), true),
            'http_code' => $response->getStatus(),
            'response' => $response,
        );
    }

    protected function getRoute(string $route) : string
    {
        if (!array_key_exists($route, $this->routes)) {
            $this->fail(
                sprintf('Route \'%s\' could not be found', $route)
            );
        }

        $path = $this->routes[$route];

        return sprintf('%s/%s/%s', $this->host, $this->environment, $path);
    }

    protected function addRoute(string $route, string $url) : LanglandTestCase
    {
        if (array_key_exists($route, $this->routes)) {
            $this->fail(
                sprintf('Route %s already exists', $url)
            );
        }

        $this->routes[$route] = $url;

        return $this;
    }

    protected function logIn(string $user, string $password)
    {
        $crawler = $this->client->request('GET', 'http://33.33.33.10/web/app_dev.php/admin/login');

        $button = $crawler->selectButton('SIGN IN');

        $form = $button->form(array(
            '_username' => $user,
            '_password' => $password,
        ));

        $this->client->submit($form);
    }
}