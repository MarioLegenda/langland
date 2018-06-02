<?php

namespace Armor\Infrastructure\Resolver;

use Armor\Infrastructure\Communicator\Session\LanguageSessionCommunicator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class LanguageSessionCommunicatorValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var LanguageSessionCommunicator $languageSessionCommunicator
     */
    private $languageSessionCommunicator;
    /**
     * LanguageValueResolver constructor.
     * @param LanguageSessionCommunicator $languageSessionCommunicator
     */
    public function __construct(
        LanguageSessionCommunicator $languageSessionCommunicator
    ) {
        $this->languageSessionCommunicator = $languageSessionCommunicator;
    }
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (LanguageSessionCommunicator::class !== $argument->getType()) {
            return false;
        }

        $languageId = $request->request->get('languageId');

        if (is_null($languageId)) {
            $languageId = $request->query->get('languageId');

            if (is_null($languageId)) {
                $languageId = $request->get('languageId');
            }
        }

        if (is_numeric($languageId)) {
            $languageId = (int) $languageId;
        }

        $this->languageSessionCommunicator->initializeSession($languageId);

        return true;
    }
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return \Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        yield $this->languageSessionCommunicator;
    }
}