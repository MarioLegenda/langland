<?php

namespace LearningSystem\Infrastructure;

use LearningSystem\Exception\ParameterException;
use LearningSystem\Infrastructure\Validator\ValidatorInterface;

class BaseParameterBag implements ParameterBagInterface, \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * @var array $parameters
     */
    protected $parameters = [];
    /**
     * BaseParameterBag constructor.
     * @param array $parameters
     * @throws ParameterException
     */
    public function __construct(array $parameters = [])
    {
        if (empty($parameters)) {
            return;
        }

        foreach ($parameters as $key => $parameter) {
            $this->add($key, $parameter);
        }
    }
    /**
     * @param string $key
     * @param $parameter
     * @throws ParameterException
     * @return ParameterBagInterface
     */
    public function add(string $key, $parameter): ParameterBagInterface
    {
        if (!$this->has($key)) {
            $this->parameters[$key] = [
                'parameter' => $parameter,
            ];

            return $this;
        }

        $message = sprintf('Parameter with key \'%s\' already exists', $key);
        throw new ParameterException($message);
    }
    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->parameters);
    }
    /**
     * @param string $key
     * @return bool
     */
    public function remove(string $key): bool
    {
        if (!$this->has($key)) {
            return false;
        }

        unset($this->parameters[$key]);

        return true;
    }
    /**
     * @param string $key
     * @param mixed $default
     * @return array
     */
    public function get(string $key, $default = null): ?array
    {
        if (!$this->has($key)) {
            return $default;
        }

        return $this->parameters[$key];
    }
    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->parameters);
    }
    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->parameters);
    }
    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->parameters);
    }
    /**
     * @param ValidatorInterface $validator
     */
    public function validate(ValidatorInterface $validator)
    {
        $validator->validate($this);
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->parameters;
    }
    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }
    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritdoc
     * @throws ParameterException
     */
    public function offsetSet($offset, $value)
    {
        $this->add($offset, $value);
    }

    /**
     * @param mixed $offset
     * @throws ParameterException
     */
    public function offsetUnset($offset)
    {
        throw new ParameterException('Cannot unset an entry in parameter bag');
    }
}