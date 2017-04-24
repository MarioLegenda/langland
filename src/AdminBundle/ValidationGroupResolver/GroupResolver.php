<?php

namespace AdminBundle\ValidationGroupResolver;

use Symfony\Component\HttpFoundation\RequestStack;

class GroupResolver
{
    /**
     * @var null|\Symfony\Component\HttpFoundation\Request
     */
    private $request;
    /**
     * CourseGroupResolver constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }
    /**
     * @return array
     */
    public function resolveValidationGroups() : array
    {
        if ($this->request->attributes->get('_route') === 'course_create' and $this->request->isMethod('post')) {
            return array('Default', 'create');
        }

        return array('Default');
    }
}