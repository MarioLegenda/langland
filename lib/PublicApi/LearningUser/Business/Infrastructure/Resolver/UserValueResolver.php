<?php

namespace PublicApi\LearningUser\Business\Infrastructure\Resolver;

use ArmorBundle\Entity\User;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class UserValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var TokenStorageInterface $tokenStorage
     */
    private $tokenStorage;
    /**
     * UserValueResolver constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        TokenStorageInterface $tokenStorage
    ) {
        $this->tokenStorage = $tokenStorage;
    }
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (User::class !== $argument->getType()) {
            return false;
        }

        $token = $this->tokenStorage->getToken();

        if (!$token instanceof TokenInterface) {
            return false;
        }

        return $token->getUser() instanceof User;
    }
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return \Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        yield $this->tokenStorage->getToken()->getUser();
    }
}