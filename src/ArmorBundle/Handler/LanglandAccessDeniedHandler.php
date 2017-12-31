<?php

namespace ArmorBundle\Handler;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class LanglandAccessDeniedHandler implements AccessDeniedHandlerInterface
{
    /**
     * @var Router $router
     */
    private $router;
    /**
     * AccessDeniedHandler constructor.
     * @param Router $router
     */
    public function __construct(
        Router $router
    ) {
        $this->router = $router;
    }
    /**
     * @param Request $request
     * @param AccessDeniedException $accessDeniedException
     * @return RedirectResponse
     */
    public function handle(
        Request $request,
        AccessDeniedException $accessDeniedException
    ) {
        return new RedirectResponse(
            $this->router->generate('armor_user_login'),
            302
        );
    }
}