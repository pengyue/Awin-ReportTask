<?php

namespace Awin\ReportTask\Behat\Context\Argument;

use Behat\Behat\Context\Argument\ArgumentResolver;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class ServiceArgumentResolver implements ArgumentResolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $serviceMap;

    /**
     * @param ContainerInterface $container
     * @param array              $serviceMap
     */
    public function __construct(ContainerInterface $container, array $serviceMap)
    {
        $this->container = $container;
        $this->serviceMap = $serviceMap;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveArguments(ReflectionClass $classReflection, array $arguments)
    {
        if ($constructor = $classReflection->getConstructor()) {
            return $this->resolveConstructorArguments($constructor, $arguments);
        }

        return $arguments;
    }

    /**
     * @param ReflectionMethod $constructor
     * @param array            $arguments
     *
     * @return array
     */
    private function resolveConstructorArguments(ReflectionMethod $constructor, array $arguments)
    {
        $constructorParameters = $constructor->getParameters();

        foreach ($constructorParameters as $position => $parameter) {
            if ($parameter->getClass() && $service = $this->resolve($parameter->getClass())) {
                $arguments[$position] = $service;
            }
        }

        return $arguments;
    }

    /**
     * @param ReflectionClass $class
     *
     * @return mixed
     */
    private function resolve(ReflectionClass $class)
    {
        if (isset($this->serviceMap[$class->getName()])) {
            return $this->container->get($this->serviceMap[$class->getName()]);
        }
    }
}