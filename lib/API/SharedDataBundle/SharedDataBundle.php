<?php

namespace API\SharedDataBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SharedDataBundle extends Bundle
{
    public function boot()
    {
        $blueDotServiceName = 'blue_dot_service_name';
        $apiDir = realpath(__DIR__.'/Resources/config/blue_dot_api');

        if (!$this->container->hasParameter($blueDotServiceName)) {
            throw new \RuntimeException(
                sprintf('Missing parameter \'%s\'. That parameter should have the name of BlueDot service so that SharedDataBundle could know that the service exists', $blueDotServiceName)
            );
        }

        $serviceName = $this->container->getParameter($blueDotServiceName);

        $blueDot = $this->container->get($serviceName);

        $dirs = $blueDot->api()->getDirs();

        if (in_array($apiDir, $dirs) === false) {
            $blueDot->api()->putAPI($apiDir);
        }
    }
}
