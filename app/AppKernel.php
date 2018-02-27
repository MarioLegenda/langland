<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new AdminBundle\AdminBundle(),
            new ArmorBundle\ArmorBundle(),
            new PublicBundle\PublicBundle(),
            new PublicApiBundle\PublicApiBundle(),
            new CommonBundle\CommonBundle(),
            new LearningSystemBundle\LearningSystemBundle()
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $env = $this->getEnvironment();

        $load = sprintf($this->getRootDir().'/config/%s/config_%s.yml', $env, $env);

        if ($env === 'prod') {
            $load = sprintf($this->getRootDir().'/config/%s/config.yml', $env, $env);
        }

        $loader->load($load);
    }

    protected function initializeContainer()
    {
        parent::initializeContainer();

        include_once __DIR__.'/ProjectBootstrap.php';

        $projectBootstrap = new ProjectBootstrap($this->getEnvironment());

        if ($projectBootstrap->isBootstrapped() === true) {
            return;
        }

        $projectBootstrap->bootstrapDirectories(
            $this->container->getParameter('image_upload'),
            array('relative_image_path')
        );
    }
}
