<?php

namespace PublicApi\Infrastructure\Resolver;

use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class QuestionAnswersResolver implements ArgumentValueResolverInterface
{
    /**
     * @var QuestionAnswers
     */
    private $questionAnswers;
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (QuestionAnswers::class !== $argument->getType()) {
            return false;
        }

        $questionAnswers = $request->request->get('questionAnswers');

        if (is_null($questionAnswers)) {
            return false;
        }

        $this->questionAnswers = new QuestionAnswers($questionAnswers);

        return true;
    }
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return \Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        yield $this->questionAnswers;
    }
}