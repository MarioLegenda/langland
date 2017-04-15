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
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
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

    public function adminLogoutAction(Request $request)
    {
        $this->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();
    }
}