<?php

namespace API\ApiBundle\Resolver;

use API\ApiBundle\Configuration\Configuration;
use BlueDot\BlueDotInterface;

class ResultResolver
{
    /**
     * @var BlueDotInterface $blueDot
     */
    private $blueDot;
    /**
     * @var Configuration $configuration
     */
    private $configuration;
    /**
     * @var array $result
     */
    private $result = array();
    /**
     * ResultResolver constructor.
     * @param BlueDotInterface $blueDot
     * @param Configuration $configuration
     */
    public function __construct(BlueDotInterface $blueDot, Configuration $configuration)
    {
        $this->blueDot = $blueDot;
        $this->configuration = $configuration;
    }
    /**
     * @return ResultResolver
     */
    public function resolve() : ResultResolver
    {
        if ($this->configuration->hasApiName()) {
            $this->blueDot->useApi($this->configuration->getApiName());
        }

        $parameters = array();

        if ($this->configuration->hasParameters()) {
            $parameters = $this->configuration->getParameters();
        }

        $promise = $this->blueDot->execute(
            $this->configuration->getStatementName(),
            $parameters
        );

        if ($promise->isSuccess()) {
            $this->result = $promise->getResult()->toArray();
        }

        return $this;
    }
    /**
     * @return array
     */
    public function getResult() : array
    {
        return $this->result;
    }
}