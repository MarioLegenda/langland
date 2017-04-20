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
     *
     * $data expects array('language' => 'french')
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
    public function findWorkingLanguageByUser(array $data)
    {
        $promise = $this->blueDot->execute('simple.select.find_working_language_by_user', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     *
     * $data array('user_id' => 1, 'language_id' => 1)
     */
    public function updateWorkingLanguage(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('scenario.update_working_language', array(
            'find_working_language' => array(
                'user_id' => $data['user_id'],
            ),
            'create_working_language' => $data,
            'remove_working_language' => array(
                'user_id' => $data['user_id'],
            ),
            'update_working_language' => $data,
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