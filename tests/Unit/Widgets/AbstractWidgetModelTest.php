<?php

namespace Ht7\Html\Tests\Unit\Widgets;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Widgets\AbstractWidgetModel;

class AbstractWidgetModelTest extends TestCase
{

    public function testConstructor()
    {
        // see: http://miljar.github.io/blog/2013/12/20/phpunit-testing-the-constructor/
        $className = AbstractWidgetModel::class;
        $data = ['data1', 'data2'];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setData'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setData')
                ->with($this->equalTo($data));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $data);
    }

    public function testGetData()
    {
        $className = AbstractWidgetModel::class;

        $data = ['data1', 'data2'];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setData'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('data');
        $property->setAccessible(true);

        $property->setValue($mock, $data);

        $this->assertEquals($data, $mock->getData());
    }

    public function testSetData()
    {
        $className = AbstractWidgetModel::class;

        $data = ['data1', 'data2'];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getData'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setData($data);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('data');
        $property->setAccessible(true);

        $this->assertEquals($data, $property->getValue($mock));
    }

}
