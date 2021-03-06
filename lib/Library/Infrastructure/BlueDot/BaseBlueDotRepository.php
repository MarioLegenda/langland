<?php

namespace Library\Infrastructure\BlueDot;

use BlueDot\BlueDot;
use BlueDot\Entity\PromiseInterface;

class BaseBlueDotRepository
{
    /**
     * @var string $apiName
     */
    protected $apiName;
    /**
     * @var BlueDot $blueDot
     */
    protected $blueDot;
    /**
     * LessonApiRepository constructor.
     * @param BlueDot $blueDot
     * @param string $apiName
     */
    public function __construct(BlueDot $blueDot, string $apiName)
    {
        $blueDot->useRepository($apiName);

        $this->blueDot = $blueDot;
        $this->apiName = $apiName;
    }
    /**
     * @param int $id
     * @param string $statement
     * @return PromiseInterface
     * @throws \BlueDot\Exception\BlueDotRuntimeException
     * @throws \BlueDot\Exception\ConnectionException
     */
    public function find(int $id, string $statement): PromiseInterface
    {
        return $this->blueDot->execute($statement, [
            'id' => $id,
        ]);
    }
    /**
     * @param string $statement
     * @return PromiseInterface
     * @throws \BlueDot\Exception\BlueDotRuntimeException
     * @throws \BlueDot\Exception\ConnectionException
     */
    public function findAll(string $statement): PromiseInterface
    {
        return $this->blueDot->execute($statement);
    }
}