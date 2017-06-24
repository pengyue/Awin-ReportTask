<?php

namespace Awin\ReportTask\App;

use Awin\ReportTask\Bundle\ReportBundle\ReportBundle;
use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use FOS\RestBundle\FOSRestBundle;
use JMS\SerializerBundle\JMSSerializerBundle;
use Nelmio\CorsBundle\NelmioCorsBundle;

/**
 * Description of AppKernel
 */
class AppKernel extends Kernel
{

    const ENVIRONMENT_DEV = 'dev';
    const ENVIRONMENT_TEST = 'test';
    const ENVIRONMENT_PROD = 'prod';

    /**
     * @return array
     */
    public function registerBundles()
    {
        $bundles = [
            new FrameworkBundle(),
            new ReportBundle(),
            new FOSRestBundle(),
            new JMSSerializerBundle(),
            new NelmioCorsBundle(),
            new NelmioApiDocBundle(),
            new MonologBundle(),
        ];

        if ($this->debug === true) {
            $bundles[] = new TwigBundle();
            $bundles[] = new DebugBundle();
            $bundles[] = new WebProfilerBundle();
        }

        return $bundles;
    }

    /**
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config.' . $this->environment . '.yml');

        if ($this->debug === true) {
            $loader->load(__DIR__ . '/config/debug/debug.yml');
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'qp_ms_template_symfony';
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return __DIR__;
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return $this->rootDir . '/../var/log/' . $this->environment;
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return $this->rootDir . '/../var/cache/' . $this->environment;
    }

    /**
     * @param string $name
     * @param string $extension
     */
    public function loadClassCache($name = 'classes', $extension = '.php')
    {
        if (!$this->debug) {
            parent::loadClassCache($name, $extension);
        }
    }

}
