<?php

namespace Hardkode;

use Assert\Assert;

/**
 * Class Initializer
 * @package Hardkode
 */
class Initializer
{

    /** @var Container */
    private static $container;

    /**
     * @param Container $container
     */
    public static function setContainer(Container $container)
    {
        self::$container = $container;
    }

    /**
     * @return Container
     */
    public static function getContainer(): Container
    {
        return self::$container;
    }

    /**
     * @param string $className
     * @return object
     *
     * @throws \ReflectionException
     */
    public static function load(string $className): object
    {
        Assert::that($className)->classExists();

        $reflection                   = new \ReflectionClass($className);
        $constructorDependencies      = self::extractParameterDependencies($reflection->getConstructor()->getParameters());
        $awareInterfaceDependencies   = self::extractAwareInterfaceDependencies($reflection->getInterfaces());
        $constructorDependencyObjects = [];

        foreach ($constructorDependencies as $parameter => $constructorDependency) {
            $constructorDependencyObjects[$parameter] = self::getContainer()->get($constructorDependency);
        }

        $obj = $reflection->newInstanceArgs($constructorDependencyObjects);

        foreach ($awareInterfaceDependencies as $setter => $awareInterfaceDependency) {
            $awareInterfaceDependencyObject = self::getContainer()->get($awareInterfaceDependency);
            $obj->$setter($awareInterfaceDependencyObject);
        }

        return $obj;
    }

    /**
     * @param \ReflectionParameter[] $parameters
     *
     * @return array
     */
    private static function extractParameterDependencies(array $parameters)
    {
        $result = [];

        foreach ($parameters as $parameter) {
            $result[$parameter->getName()] = $parameter->getClass()->getName();
        }

        return $result;
    }

    /**
     * @param \ReflectionClass[] $interfaces
     */
    private static function extractAwareInterfaceDependencies(array $interfaces)
    {
        $result = [];

        foreach ($interfaces as $interface) {

            foreach ( $interface->getMethods() as $method) {

                if (strpos($method->getName(), 'set') !== 0) {
                    continue;
                }

                $result[$method->getName()] = $method->getParameters()[0]->getClass()->getName();

            }
        }

        return $result;
    }

}