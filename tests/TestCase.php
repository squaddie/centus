<?php

namespace Tests;

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
     *@throws ReflectionException
     */
    protected function invokeMethod(&$object, string $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
