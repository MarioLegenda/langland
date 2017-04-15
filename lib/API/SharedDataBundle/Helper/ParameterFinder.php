<?php

namespace API\SharedDataBundle\Helper;

use Symfony\Component\HttpFoundation\Request;

class ParameterFinder
{
    public static function findParameters(Request $request, array $parameters)
    {
        $foundParameters = array();

        foreach ($parameters as $parameter) {
            $found = self::findSingleParameter($request, $parameter);

            if (empty($found)) {
                return null;
            }

            $foundParameters[$parameter] = $found;
        }

        return $foundParameters;
    }

    public static function findSingleParameter(Request $request, string $parameter)
    {
        $found = null;
        if (is_null($request->get($parameter))) {
            $realRequest = null;

            if ($request->getMethod() === 'GET') {
                $realRequest = $request->query;
            } else if ($request->getMethod() === 'POST') {
                $realRequest = $request->request;
            }

            if (!$realRequest->has($parameter)) {
                return null;
            }

            $found = $request->get($parameter);
        } else {
            $found = $request->get($parameter);
        }

        return $found;
    }
}