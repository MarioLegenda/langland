<?php

namespace API\ApiBundle\Configuration;

use Symfony\Component\HttpFoundation\RequestStack;

class Configuration
{
    /**
     * @var array $valid
     */
    private $valid = array(
        'valid' => false,
        'message' => null
    );
    /**
     * @var string $statementName
     */
    private $statementName;
    /**
     * @var string $apiName
     */
    private $apiName;
    /**
     * @var array $parameters
     */
    private $parameters;
    /**
     * Configuration constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();

        if ($request->request->has('blue_dot')) {
            $config = json_decode($request->request->get('blue_dot'), true);

            if (array_key_exists('api', $config)) {
                $this->apiName = $config['api'];
            }

            if (!array_key_exists('statement', $config)) {
                $this->valid['message'] = sprintf('\'%s\' key not found', 'statement');

                return;
            }

            if (array_key_exists('statement', $config)) {
                $this->valid['valid'] = true;

                $statement = $config['statement'];

                $this->statementName = array_keys($statement)[0];

                $this->parameters = $statement[array_keys($statement)[0]];

                return;
            }
        }
    }
    /**
     * @return string
     */
    public function getStatementName() : string
    {
        return $this->statementName;
    }
    /**
     * @return bool
     */
    public function hasApiName() : bool
    {
        return is_string($this->apiName);
    }
    /**
     * @return string
     */
    public function getApiName() : string
    {
        return $this->apiName;
    }
    /**
     * @return bool
     */
    public function hasParameters() : bool
    {
        return is_array($this->parameters);
    }
    /**
     * @return array
     */
    public function getParameters() : array
    {
        return $this->parameters;
    }
    /**
     * @return array
     */
    public function getValidity() : array
    {
        return $this->valid;
    }
}