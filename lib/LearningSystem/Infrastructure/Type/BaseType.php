<?php

namespace LearningSystem\Infrastructure\Type;

class BaseType implements TypeInterface
{
    /**
     * @var array $type
     */
    protected $type;
    /**
     * BaseType constructor.
     * @param array $type
     */
    public function __construct(array $type)
    {
        $this->type = $type;
    }
    /**
     * @param mixed $value
     * @return TypeInterface
     */
    public static function fromValue($value): TypeInterface
    {
        foreach (static::$types as $key => $type) {
            if ($value === $type) {
                return new static([$key => $type]);
            }
        }

        throw new \RuntimeException(sprintf('%s could not be created from value %s', static::class, (string) $value));
    }
    /**
     * @param $key
     * @return static
     */
    public static function fromKey($key): TypeInterface
    {
        foreach (static::$types as $k => $type) {
            if ($k === $key) {
                return new static([$k => $type]);
            }
        }

        throw new \RuntimeException(sprintf('%s could not be created from key', static::class));
    }
    /**
     * @param $key
     * @return bool
     */
    public function isTypeByKey($key): bool
    {
        return array_key_exists($key, $this->type);
    }
    /**
     * @param $value
     * @return bool
     */
    public function isTypeByValue($value): bool
    {
        $v = array_values($this->type)[0];

        return $v === $value;
    }
    /**
     * @return mixed
     */
    public function getKey()
    {
        return array_keys($this->type)[0];
    }
    /**
     * @return mixed
     */
    public function getValue()
    {
        return array_values($this->type)[0];
    }
    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function equals(TypeInterface $type): bool
    {
        return $this->equalsKey($type->getKey()) && $this->equalsValue($type->getValue());
    }
    /**
     * @param $value
     * @return bool
     */
    public function equalsValue($value): bool
    {
        return $value === $this->getValue();
    }
    /**
     * @param $key
     * @return bool
     */
    public function equalsKey($key): bool
    {
        return $key === $this->getKey();
    }
    /**
     * @param array $range
     * @return bool
     */
    public function inValueRange(array $range): bool
    {
        foreach ($range as $option) {
            if ($option === $this->getValue()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @param array $range
     * @return bool
     */
    public function inKeyRange(array $range): bool
    {
        foreach ($range as $option) {
            if ($option === $this->getKey()) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getValue();
    }
}