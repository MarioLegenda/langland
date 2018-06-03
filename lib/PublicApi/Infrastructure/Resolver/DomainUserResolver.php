<?php

namespace PublicApi\Infrastructure\Resolver;

use ArmorBundle\Entity\User as ArmorUser;
use Library\Infrastructure\Helper\SerializerWrapper;
use PublicApi\Infrastructure\Model\User as PublicApiUser;
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
     * @var SerializerWrapper $serializerWrapper
     */
    private $serializerWrapper;
    /**
     * @var PublicApiUser $user
     */
    private $user;
    /**
     * UserValueResolver constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param SerializerWrapper $serializerWrapper
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        SerializerWrapper $serializerWrapper
    ) {
        $this->serializerWrapper = $serializerWrapper;
        $this->tokenStorage = $tokenStorage;
    }
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (PublicApiUser::class !== $argument->getType()) {
            return false;
        }

        $token = $this->tokenStorage->getToken();

        if (!$token instanceof TokenInterface) {
            return false;
        }

        $supports = $token->getUser() instanceof PublicApiUser;

        if ($supports) {
            /** @var PublicApiUser $publicApiUser */
            $publicApiUser = $this->serializerWrapper->convertFromTo(
                $token->getUser(),
                ['communication_model'],
                PublicApiUser::class
            );

            $this->user = $publicApiUser;
        }

        return $supports;
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