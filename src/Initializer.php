<?php

namespace Hardkode;

use Assert\Assert;

/**
 * Class Initializer
 * @package Hardkode
 */
class Initializer
{

    /**
     * @param string $className
     * @return object
     *
     * @throws \ReflectionException
     */
    public static function load(string $className): object
    {
        Assert::that($className)->classExists();

        $obj = new $className();

        $reflection = new \ReflectionClass($obj);

        $constructorDependencies = self::extractParameterDependencies($reflection->getConstructor()->getParameters());

        $awarePatternDependencies = '';

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
            $result[] = $parameter->getClass();
        }

        return $result;
    }

}