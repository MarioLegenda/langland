<?php

namespace ArmorBundle\Controller;

use ArmorBundle\Admin\UserLoggedInInterface;
use ArmorBundle\Entity\Role;
use ArmorBundle\Entity\User;
use ArmorBundle\Event\AccountConfirmedEvent;
use ArmorBundle\Exception\AccountNotEnabledException;
use ArmorBundle\Form\Type\RegistrationForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class UserSecurityController extends Controller implements UserLoggedInInterface
{
    public function userLoginAction()
    {
        $doRedirect = $this->tryRedirectIfAuthorized();
        if ($doRedirect !== null) {
            return $doRedirect;
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        $errorMessage = '';
        if ($error instanceof BadCredentialsException) {
            $errorMessage = 'Username or password are incorrect';
        } else if ($error instanceof AccountNotEnabledException) {
            $errorMessage = $error->getMessage();
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('ArmorBundle:Security:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $errorMessage,
        ));
    }

    public function userLogoutAction(Request $request)
    {
        $this->get('security.token_storage')->setToken(null);

        return $this->redirectToRoute('armor_user_login');
    }

    public function registerAction(Request $request)
    {
        $doRedirect = $this->tryRedirectIfAuthorized();

        if ($doRedirect !== null) {
            return $doRedirect;
        }

        $securityContext = $this->get('security.authorization_checker');

        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_page_main_dashboard');
        }
        
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $user->addRole(new Role('ROLE_USER'));
            $user->setEnabled(0);

            $userRepo = $this->get('doctrine')->getRepository('ArmorBundle:User');
            for (;;) {
                $hash = md5(uniqid(rand(), true));

                if (empty($userRepo->findUserByConfirmationHash($hash))) {
                    $user->setConfirmHash($hash);

                    break;
                }
            }

            $confirm = $this->get('armor.email')->send(
                'confirmation_email',
                $user->getUsername(),
                array('confirm_url' => 'http://'.$this->getParameter('host').$this->generateUrl('armor_user_confirm', array(
                    'hash' => $user->getConfirmHash(),
                ), UrlGenerator::ABSOLUTE_PATH))
            );

            if ($confirm !== 1) {
                $this->addFlash(
                    'email_failed',
                    sprintf('Email sent to %s failed. Please, try again', $user->getUsername())
                );

                return $this->render(
                    'ArmorBundle:Security:register.html.twig', array(
                        'form' => $form->createView(),
                    )
                );
            }

            $em = $this->get('doctrine')->getManager();

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'user_created_notice',
                sprintf('Your account has be successfully created and a confirmation email has been sent to %s', $user->getUsername())
            );


            return $this->redirectToRoute('armor_user_login');
        }

        return $this->render(
            'ArmorBundle:Security:register.html.twig', array(
                'form' => $form->createView(),
            )
        );
    }

    public function confirmUserAction($hash)
    {
        $userRepo = $this->get('doctrine')->getRepository('ArmorBundle:User');

        $user = $userRepo->findUserByConfirmationHash($hash);

        if (!empty($user)) {
            $this->addFlash(
                'user_confirmed',
                sprintf('Thank you, %s. You have successfully confirmed your account. Now, sign in and start learning a new language', $user[0]->getUsername())
            );

            $user = $user[0];

            $user->setConfirmHash(null);
            $user->setEnabled(true);

            $em = $this->get('doctrine')->getManager();
            $em->persist($user);
            $em->flush();

            $dispatcher = $this->get('event_dispatcher');
            $event = new AccountConfirmedEvent($user);
            $dispatcher->dispatch(AccountConfirmedEvent::NAME, $event);

            return $this->redirectToRoute('armor_user_login');
        }

        $this->addFlash(
            'user_not_confirmed',
            sprintf('For some reason, you email %s could not be confirmed. Please, sign up with another email', $user[0]->getUsername())
        );

        return $this->redirectToRoute('armor_user_login');
    }

    private function tryRedirectIfAuthorized()
    {
        $securityContext = $this->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->getUser()->hasRole('ROLE_USER')) {
                return $this->redirectToRoute('app_dashboard');
            }

            return null;
        }
    }
}