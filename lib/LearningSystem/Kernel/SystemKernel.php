<?php

namespace LearningSystem\Kernel;

use LearningSystem\Algorithm\Initial\Parameter\Contract\AlgorithmParameterInterface;

class SystemKernel
{
    /**
     * @var AlgorithmParameterInterface $algorithmParameters
     */
    private $algorithmParameters;
    /**
     * SystemKernel constructor.
     * @param AlgorithmParameterInterface $algorithmParameters
     */
    public function __construct(
        AlgorithmParameterInterface $algorithmParameters
    ) {
        $this->algorithmParameters = $algorithmParameters;
    }
}