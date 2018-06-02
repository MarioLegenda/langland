<?php

namespace Library\Util;

class Util
{
    /**
     * @param array $toGenerate
     * @return \Generator
     */
    public static function createGenerator(array $toGenerate): \Generator
    {
        foreach ($toGenerate as $key => $item) {
            yield ['key' => $key, 'item' => $item];
        }
    }
    /**
     * @param \DateTime|string $dateTime
     * @return \DateTime
     */
    public static function toDateTime($dateTime): \DateTime
    {
        if ($dateTime instanceof \DateTime) {
            return $dateTime;
        }

        $dateTime = \DateTime::createFromFormat('Y-m-d H:m:s', $dateTime);

        if (!$dateTime instanceof \DateTime) {
            $message = sprintf('Invalid date time in %s', Lesson::class);
            throw new \RuntimeException($message);
        }

        return $dateTime;
    }
    /**
     * @param object $object
     * @param string $field
     * @return mixed
     */
    public static function extractFieldFromObject(object $object, string $field)
    {
        return $object->{'get'.ucfirst($field)}();
    }
    /**
     * @param array|iterable $objects
     * @param string $field
     * @return array
     */
    public static function extractFieldFromObjects(iterable $objects, string $field): array
    {
        $fields = [];
        foreach ($objects as $object) {
            $fields[] = Util::extractFieldFromObject($object, $field);
        }

        return $fields;
    }
}