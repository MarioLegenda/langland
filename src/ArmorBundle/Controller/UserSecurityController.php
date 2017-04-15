<?php

namespace ArmorBundle\Controller;

use ArmorBundle\Admin\UserLoggedInInterface;
use ArmorBundle\Entity\User;
use ArmorBundle\Form\Type\RegistrationForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class UserSecurityController extends Controller implements UserLoggedInInterface
{
    public function userLoginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error instanceof BadCredentialsException) {
            $error = 'Username or password are incorrect. If you already registered, please confirm your password from the email that you received';
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('ArmorBundle:Security:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    public function userLogoutAction(Request $request)
    {
        $this->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();
    }

    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $user->addRole('ROLE_USER');

            return $this->get('armor.repository.user_repository')
                ->createUser($user)
                ->success(function() use ($user) {
                    $this->addFlash(
                        'user_created_notice',
                        sprintf('You have successfully registered. A confirmation email has been sent to %s', $user->getUsername())
                    );

                    $this->get('armor.email')->send(
                        'confirmation_email',
                        $user->getUsername(),
                        array('confirm_hash' => 'člgjačfdhgodilkfhgo8irtu39487529487524wu8tef')
                    );

                    return new RedirectResponse($this->generateUrl('armor_user_login').'?action=sign-in');
                })
                ->failure(function() use ($form, $user) {
                    $form->addError(new FormError(sprintf('User with username %s already exists', $user->getUsername())));

                    return $this->render(
                        'ArmorBundle:Security:register.html.twig', array(
                            'form' => $form->createView(),
                        )
                    );
                })
                ->getResult();
        }

        return $this->render(
            'ArmorBundle:Security:register.html.twig', array(
                'form' => $form->createView(),
            )
        );
    }
}