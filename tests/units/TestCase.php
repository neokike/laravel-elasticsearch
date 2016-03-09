<?php
namespace Test\units;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param $argName
     * @return mixed Method return.
     * @internal param string $methodName Method name to call
     * @internal param array $parameters Array of parameters to pass into method.
     *
     */
    public function invokeArgument(&$object, $argName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $arg = $reflection->getProperty($argName);
        $arg->setAccessible(true);

        return $arg;
    }
}