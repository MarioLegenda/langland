<?php

namespace ApiSDK;

class ApiSDK
{
    /**
     * @var array $data
     */
    private $data;
    /**
     * @var array $config
     */
    private $config = [
        'metadata' => [
            'properties' => [],
            'method' => null,
            'type' => null,
            'statusCode' => null,
        ],
        'collection' => [
            'totalItems' => null,
            'data' => null,
        ],
        'resource' => [
            'data' => null,
        ],
    ];
    /**
     * @var array $metadata
     */
    private $metadata = [
        'isCollection' => false,
        'isResource' => false,
    ];
    /**
     * @var bool $createCalledFlag
     */
    private $createCalledFlag = false;
    /**
     * @param array $data
     * @return ApiSDK
     */
    public function create(array $data): ApiSDK
    {
        $this->createCalledFlag = true;

        $this->data = $data;

        return $this;
    }
    /**
     * @return ApiSDK
     * @throws \RuntimeException
     */
    public function isResource(): ApiSDK
    {
        $this->createCalled();

        if ($this->metadata['isCollection'] === true) {
            $message = 'This api builder is already a collection. A api response cannot be a resource and a collection at the same time';
            throw new \RuntimeException($message);
        }

        $this->metadata['isResource'] = true;

        return $this;
    }
    /**
     * @param string $method
     * @return ApiSDK
     */
    public function method(string $method): ApiSDK
    {
        $this->createCalled();

        $this->config['metadata']['method'] = $method;

        return $this;
    }
    /**
     * @return ApiSDK
     * @throws \RuntimeException
     */
    public function isCollection(): ApiSDK
    {
        $this->createCalled();

        if ($this->metadata['isResource'] === true) {
            $message = 'This api builder is already a resource. An api response cannot be a resource and a collection at the same time';
            throw new \RuntimeException($message);
        }

        $this->metadata['isCollection'] = true;

        return $this;
    }
    /**
     * @param int $statusCode
     * @return ApiSDK
     */
    public function setStatusCode(int $statusCode): ApiSDK
    {
        $this->createCalled();

        $this->config['metadata']['statusCode'] = $statusCode;

        return $this;
    }
    /**
     * @return array
     */
    public function build(): array
    {
        $this->createCalled();

        $this->validate();

        $metadata = $this->buildMetadata();
        $data = $this->buildData();

        $this->reset();

        return array_merge($metadata, $data);
    }
    /**
     * @return ApiSDK
     */
    public function reset(): ApiSDK
    {
        $this->data = null;
        $this->config = [];

        $this->metadata = [
            'isCollection' => false,
            'isResource' => false,
        ];

        return $this;
    }
    /**
     * @return array
     */
    private function buildMetadata(): array
    {
        $properties = [];
        $method = null;
        $type = null;

        if (!empty($this->data)) {
            $properties = array_keys($this->data);
        }

        $method = $this->config['metadata']['method'];
        $type = $this->determineType();
        $statusCode = $this->config['metadata']['statusCode'];

        return [
            'properties' => $properties,
            'method' => $method,
            'type' => $type,
            'statusCode' => $statusCode,
        ];
    }
    /**
     * @return array
     */
    private function buildData(): array
    {
        $type = $this->determineType();

        if ($type === 'collection') {
            return [
                'collection' => [
                    'totalItems' => count($this->data),
                    'data' => $this->data,
                ],
            ];
        }

        if ($type === 'resource') {
            return [
                'resource' => ['data' => $this->data],
            ];
        }
    }
    /**
     * @return string
     */
    private function determineType(): string
    {
        return ($this->metadata['isCollection']) === true ? 'collection' : 'resource';
    }
    /**
     * @throws \RuntimeException
     */
    private function createCalled()
    {
        if ($this->createCalledFlag === false) {
            throw new \RuntimeException('ApiSDK::create(array) has to be the first method called on this object');
        }
    }
    /**
     * @throws \RuntimeException
     */
    private function validate()
    {
        $isResource = $this->metadata['isResource'];
        $isCollection = $this->metadata['isCollection'];

        if (is_null($isCollection) and is_null($isResource)) {
            throw new \RuntimeException('You have to specify weather an api response is a resource or a collection');
        }

        $method = $this->config['metadata']['method'];

        if (!is_string($method) and empty($method)) {
            throw new \RuntimeException('Method has to be specified');
        }

        $statusCode = $this->config['metadata']['statusCode'];

        if (is_null($statusCode) or !is_int($statusCode)) {
            throw new \RuntimeException('Status code cannot be null');
        }
    }
}