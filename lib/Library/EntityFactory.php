<?php

namespace Library;

class EntityFactory
{
    public function createEntity($entity, array $fields, \Closure $afterCreateEvent = null)
    {
        if (!is_object($entity) and !is_string($entity)) {
            throw new \Exception('EntityFactory did not receive an object or a valid class for population');
        }

        if (is_object($entity)) {
            return $this->create($entity, $fields, $afterCreateEvent);
        }

        if (is_string($entity)) {
            if (!class_exists($entity)) {
                throw new \Exception('EntityFactory did not receive an object or a valid class for population. The class does not exist');
            }

            return $this->create(new $entity(), $fields, $afterCreateEvent);
        }

        throw new \Exception('EntityFactory did not receive an object or a valid class for population');
    }

    private function create($entity, array $fields, \Closure $afterCreateEvent = null)
    {
        $this->populateEntity($entity, $fields);

        if ($afterCreateEvent instanceof \Closure) {
            $afterCreateEvent($entity);
        }

        return $entity;
    }

    private function populateEntity($entity, array $fields)
    {
        array_walk($fields, function($value, $field) use ($entity) {
            $method = 'set'.ucfirst($field);

            if (!method_exists($entity, $method)) {
                throw new \Exception(
                    sprintf('EntityCreator could not find method %s on object %s', $method, get_class($entity))
                );
            }

            $entity->{$method}($value);
        });
    }
}