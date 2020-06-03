<?php

namespace Hardkode;

use Assert\Assert;
use Hardkode\Exception\NotFoundException;
use Hardkode\View\Helper\Block;
use Hardkode\View\Helper\EndBlock;
use Hardkode\View\Helper\RenderBlock;
use Hardkode\View\Renderer;

/**
 * Class Initializer
 * @package Hardkode
 */
class Initializer
{

    /** @var array */
    private static $cache = [];

    /** @var Container */
    private static $container;

    private const EXCLUDE_FROM_CACHE = [
    ];

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
     * @param array  $constructorArgs
     * @return object
     *
     * @throws \ReflectionException
     */
    public static function load(string $className, $constructorArgs = []):? object
    {
        Assert::that($className)->classExists();

        $reflection                   = new \ReflectionClass($className);
        $constructorDependencies      = self::extractParameterDependencies($reflection->getConstructor()->getParameters());
        $awareInterfaceDependencies   = self::extractAwareInterfaceDependencies($reflection->getInterfaces());
        $constructorDependencyObjects = [];

//        foreach ($constructorDependencies as $parameter => $constructorDependency) {
//
//            try {
//                $constructorDependencyObjects[$parameter] = self::getContainer()->get($constructorDependency);
//            } catch (NotFoundException $e) {
//                self::$cache[$constructorDependency] = Initializer::load($constructorDependency);
//                $constructorDependencyObjects[$parameter] = self::$cache[$constructorDependency];
//            }
//
//        }

        $obj = $reflection->newInstanceArgs($constructorArgs);

        foreach ($awareInterfaceDependencies as $setter => $awareInterfaceDependency) {

            try {
                $awareInterfaceDependencyObject = self::getContainer()->get($awareInterfaceDependency);
            } catch (NotFoundException $e) {
                self::$cache[$awareInterfaceDependency] = Initializer::load($awareInterfaceDependency);
                $awareInterfaceDependencyObject = self::$cache[$awareInterfaceDependency];
            }

            $obj->$setter($awareInterfaceDependencyObject);

        }

        return $obj;
    }

    private static function getConstructorDependencies()
    {

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

            if (null === $parameter->getClass()) {
                continue;
            }

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