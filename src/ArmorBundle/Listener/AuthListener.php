<?php

namespace ArmorBundle\Listener;

use ArmorBundle\Admin\AdminAuthInterface;
use ArmorBundle\Admin\UserLoggedInInterface;
use ArmorBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpKernel\KernelEvents;

class AuthListener implements EventSubscriberInterface
{
    /**
     * @var AuthorizationChecker $authChecker
     */
    private $authChecker;
    /**
     * @var null|\Symfony\Component\HttpFoundation\Request $request
     */
    private $request;
    /**
     * @var TokenStorage $tokenStorage
     */
    private $tokenStorage;
    /**
     * @var Router $router
     */
    private $router;
    /**
     * AuthListener constructor.
     * @param AuthorizationChecker $authorizationChecker
     * @param RequestStack $requestStack
     * @param TokenStorage $tokenStorage
     * @param Router $router
     */
    public function __construct(
        AuthorizationChecker $authorizationChecker,
        RequestStack $requestStack,
        TokenStorage $tokenStorage,
        Router $router
    )
    {
        $this->authChecker = $authorizationChecker;
        $this->request = $requestStack->getCurrentRequest();
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array(
                array('onControllerAuth', 0),
                array('onRequestArguments', 10),
            ),
        );
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onControllerAuth(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof AdminAuthInterface) {
            try {
                if (!$this->authChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
                    throw new AccessDeniedException();
                }

                if (!$this->authChecker->isGranted('ROLE_DEVELOPER')) {
                    throw new AccessDeniedException();
                }
            } catch (AccessDeniedException $e) {
                $this->invalidateUserAndRedirect($event);
            }
        }

        if ($controller[0] instanceof UserLoggedInInterface) {
            if ($this->authChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
                $this->redirectIfLoginPage($event);
            }
        }
    }

    public function onRequestArguments(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if ($this->request->getMethod() === 'GET') {
            return;
        }

        $controllers = array(
            'TheoryController::createAction' => array('name', 'lesson_id'),
            'TheoryController::renameAction' => array('lesson_id', 'theory_id', 'name'),
            'TheoryController::createTheoryDeckAction' => array('internal_name', 'theory_id'),
            'TheoryController::findAllDecksByTheoryAction' => array('theory_id'),
            'TheoryController::findDeckAction' => array('deck_id'),
            'TheoryController::findAllByLessonAction' => array('lesson_id'),
            'TheoryController::findDeckSoundsAction' => array('deck_id'),
            'TheoryController:findDeckByInternalNameAction' => array('theory_id', 'internal_name'),

            'WordController::createAction' => array('word', 'translations'),
            'WordController::scheduleRemovalAction' => array('word_id'),
            'WordController::removeAction' => array('word_id'),

            'CourseController::createAction' => array('language_id', 'name'),
            'CourseController::getAllAction' => array('language_id'),
            'CourseController::getInitialCourseInfoAction' => array('id'),

            'SearchController::searchAction' => array('language_id', 'word'),
            'SearchController::lastWordsAction' => array('language_id', 'offset'),

            'LessonController::createAction' => array('class_id', 'name'),
            'LessonController::renameLessonAction' => array('class_id', 'name', 'lesson_id'),
            'LessonController::findLessonsByClassAction' => array('class_id'),

            'CategoryController::createAction' => array('category'),
            'CategoryController::getAllAction' => array('language_id'),

            'ClassController::createAction' => array('name', 'course_id'),
            'ClassController::updateAction' => array('name', 'class_id', 'course_id'),
            'ClassController::findClassesByCourseAction' => array('course_id'),

            'LanguageController::createAction' => array('language'),
            'LanguageController::updateWorkingLanguageAction' => array('working_language', 'language_id'),

            'SentenceController::createAction' => array('lesson_id'),
            'SentenceController::findInternalNamesAction' => array('lesson_id'),
            'SentenceController::findLessonSentenceAction' => array('internal_name', 'lesson_id'),
        );

        $controllerInfo = preg_split('#::#', $this->request->attributes->get('_controller'));

        // for controllers as services and symfony profiler
        if (!array_key_exists(1, $controllerInfo)) {
            return;
        }

        $splittedNamespace = preg_split('#\\\#', $controllerInfo[0]);

        $controller = $splittedNamespace[count($splittedNamespace) - 1];
        $action = $controllerInfo[1];
        $resolved = sprintf('%s::%s', $controller, $action);

        if (array_key_exists($resolved, $controllers)) {
            $requiredArguments = $controllers[$resolved];

            $allRequestArguments = array_keys($this->request->request->all());

            foreach ($requiredArguments as $argument) {
                if (in_array($argument, $allRequestArguments) === false) {

                    $this->invalidateUserAndRedirect($event);

                    return;
                }
            }
        }
    }

    private function invalidateUserAndRedirect(FilterControllerEvent $event)
    {
        $this->tokenStorage->setToken(null);
        $this->request->getSession()->invalidate();

        $redirectUrl = $this->router->generate('armor_admin_login');
        $event->setController(function() use ($redirectUrl) {
            $response = new JsonResponse(array(
                'status' => 'failure',
                'redirect_url' => $redirectUrl,
            ));

            $response->setStatusCode(403);

            return $response;
        });
    }

    private function redirectIfLoginPage(FilterControllerEvent $event)
    {
        $route = $this->request->attributes->get('_route');

        if ($route === 'armor_user_login') {
            $user = $this->tokenStorage->getToken()->getUser();

            if ($user instanceof User) {
                if ($user->hasRole('ROLE_USER')) {
                    $event->setController(function() {
                        return new RedirectResponse($this->router->generate('user_panel'));
                    });
                }
            }
        } else if ($route === 'armor_admin_login') {
            $user = $this->tokenStorage->getToken()->getUser();

            if ($user instanceof User) {
                if ($user->hasRole('ROLE_DEVELOPER')) {
                    $event->setController(function() {
                        return new RedirectResponse($this->router->generate('admin_panel'));
                    });
                }
            }
        }
    }
}
