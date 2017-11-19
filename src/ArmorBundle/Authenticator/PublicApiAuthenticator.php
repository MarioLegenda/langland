<?php

namespace ArmorBundle\Authenticator;

use ArmorBundle\Entity\User;
use ArmorBundle\Provider\PublicApiUserProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class PublicApiAuthenticator implements SimplePreAuthenticatorInterface
{
    /**
     * @var Session $session
     */
    private $session;
    /**
     * @var array $rootFirewalls
     */
    private $rootFirewalls;
    /**
     * PublicApiAuthenticator constructor.
     * @param Session $session
     * @param array $rootFirewalls
     */
    public function __construct(
        Session $session,
        array $rootFirewalls
    ) {
        $this->session = $session;
        $this->rootFirewalls = $rootFirewalls;
    }
    /**
     * @param Request $request
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    public function createToken(Request $request, $providerKey)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('No!')
            );
        }

        $username = $request->headers->get('x-langland-public-api');

        if (!$username) {
            throw new BadCredentialsException();
        }

        return new PreAuthenticatedToken(
            'anon.',
            $username,
            $providerKey
        );
    }
    /**
     * @param TokenInterface $token
     * @param $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }
    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    public function authenticateToken(
        TokenInterface $token,
        UserProviderInterface $userProvider,
        $providerKey
    ) {
        if (!$userProvider instanceof PublicApiUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of PublicApiUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $username = $token->getCredentials();

        $user = $this->authByRootFirewall($userProvider, $username);

        return new PreAuthenticatedToken(
            $user,
            $username,
            $providerKey,
            $user->getRoles()
        );
    }
    /**
     * @param UserProviderInterface $userProvider
     * @param string $username
     * @return User
     */
    public function authByRootFirewall(
        UserProviderInterface $userProvider,
        string $username
    ): User {
        $user = null;

        foreach ($this->session->all() as $firewallName => $userPasswordToken) {
            if (in_array($firewallName, $this->rootFirewalls) === true) {
                $token = unserialize($userPasswordToken);

                if (!$token instanceof UsernamePasswordToken) {
                    throw new CustomUserMessageAuthenticationException(
                        sprintf('No!')
                    );
                }

                try {
                    /** @var User $user */
                    $user = $userProvider->loadUserByUsername($username);
                    /** @var User $adminUser */
                    $adminUser = $token->getUser();

                    if ($user->getUsername() !== $adminUser->getUsername()) {
                        continue;
                    }
                } catch (UsernameNotFoundException $e) {
                }

                break;
            }
        }

        if (!$user instanceof User) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('No!')
            );
        }

        return $user;
    }
}