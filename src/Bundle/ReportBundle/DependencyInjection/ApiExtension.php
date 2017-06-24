<?php

namespace Awin\ReportTask\Bundle\ReportBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Description of ApiExtension
 */
class ApiExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->processConfiguration(new Configuration(), $configs);

        $loader = new YamlFileLoader(
            $container,
            new \Symfony\Component\Config\FileLocator([
                __DIR__ . '/../Resources/config/services'
            ])
        );

        $loader->load('controllers.yml');
    }

}