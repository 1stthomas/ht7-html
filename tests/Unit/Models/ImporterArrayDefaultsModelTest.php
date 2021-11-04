<?php

namespace Ht7\Html\Tests\Unit\Models;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Models\ImporterArrayDefaultsModel;

class ImporterArrayDefaultsModelTest extends TestCase
{

    public function testConstructorWith()
    {
        $className = ImporterArrayDefaultsModel::class;

        $callback = ['callback' => ['method' => 'testMethod']];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setCallback'])
                ->getMock();

        $mock->expects($this->once())
                ->method('setCallback')
                ->with($callback['callback']);

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $callback);
    }

    public function testConstructorWithout()
    {
        $className = ImporterArrayDefaultsModel::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setCallback'])
                ->getMock();

        $mock->expects($this->never())
                ->method('setCallback');

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock);
    }

    public function testGetCallback()
    {
        $className = ImporterArrayDefaultsModel::class;

        $callback = ['method' => 'testMethod'];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setCallback'])
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('callback');
        $property->setAccessible(true);
        $property->setValue($mock, $callback);

        $this->assertEquals($callback, $mock->getCallback());
    }

    public function testSetCallback()
    {
        $className = ImporterArrayDefaultsModel::class;

        $callback = ['method' => 'testMethod'];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getCallback'])
                ->getMock();

        $mock->setCallback($callback);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('callback');
        $property->setAccessible(true);

        $this->assertEquals($callback, $property->getValue($mock));
    }

}
