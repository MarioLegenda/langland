<?php

namespace API\SharedDataBundle\Repository;

use BlueDot\BlueDotInterface;

class LanguageRepository extends AbstractRepository
{
    /**
     * @var BlueDotInterface $blueDot
     */
    private $blueDot;
    /**
     * LanguageRepository constructor.
     * @param BlueDotInterface $blueDot
     */
    public function __construct(BlueDotInterface $blueDot)
    {
        $blueDot->useApi('language');

        $this->blueDot = $blueDot;
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function create(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.insert.create_language', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findLanguageByLanguage(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_language_by_language', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findLanguageById(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_language_by_id', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @return ResultResolver
     */
    public function findAll() : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_all_languages');

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function updateWorkingLanguage(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('scenario.update_working_language', array(
            'update_working_language' => $data
        ));

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function updateLanguageName(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.update.update_language_name', $data);

        return $this->createResultResolver($promise);
    }
}