<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use ReflectionClass;
use ReflectionException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Call protected/private method of a class.
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to all call.
     * @param array $parameters Array of parameters to be pass into method.
     * @return mixed Method return.
     * @throws ReflectionException
     */
    protected function invokeMethod(&$object, string $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @param Model $model
     * @param array $original
     */
    protected function setModelOriginal(Model $model, array $original): void
    {
        $reflection = new ReflectionClass($model);
        $property = $reflection->getProperty('original');
        $property->setAccessible(true);
        $property->setValue($model, $original);
    }

    /**
     * For setting private or protected property of an object
     * @param mixed $object
     * @param mixed $property
     * @param mixed $value
     * @throws ReflectionException
     */
    public function setProperty(mixed $object, mixed $property, mixed $value): void
    {
        $reflection = new ReflectionClass($object);
        $reflectionProperty = $reflection->getProperty($property);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);
    }
}
