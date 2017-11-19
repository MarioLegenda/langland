<?php

namespace ArmorBundle\Controller;

use ArmorBundle\Admin\UserLoggedInInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class AdminSecurityController extends Controller implements UserLoggedInInterface
{
    public function adminLoginAction(Request $request)
    {
        $securityContext = $this->get('security.authorization_checker');

        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('admin_dashboard');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error instanceof BadCredentialsException) {
            $error = 'Username or password are incorrect';
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('ArmorBundle:Security:adminLogin.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'action_path' => 'armor_admin_login'
        ));
    }

    public function adminLogoutAction()
    {
        $this->get('security.token_storage')->setToken(null);

        return $this->redirectToRoute('armor_admin_login');
    }
}