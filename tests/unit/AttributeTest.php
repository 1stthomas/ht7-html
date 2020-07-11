<?php

namespace Ht7\Base\Tests;

use \InvalidArgumentException;
use \stdClass;
use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Attribute;

/**
 * Test class for the SelfClosing class.
 *
 * @author      Thomas Pluess
 * @since       0.0.1
 * @version     0.0.1
 * @copyright (c) 2019, Thomas Pluess
 */
class AttributeTest extends TestCase
{

    public function testConstructor()
    {
        // see: http://miljar.github.io/blog/2013/12/20/phpunit-testing-the-constructor/
        $className = Attribute::class;
        $name = 'class';
        $value = 'btn btn-primary';

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setName', 'setValue'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setName')
                ->with($this->equalTo($name));
        $mock->expects($this->once())
                ->method('setValue')
                ->with($this->equalTo($value));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $name, $value);
    }

    public function testGetName()
    {
        $className = Attribute::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setName'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('name');
        $property->setAccessible(true);

        $expected = 'class';

        $property->setValue($mock, $expected);

        $this->assertEquals($expected, $mock->getName());
    }

    public function testGetValue()
    {
        $className = Attribute::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setValue'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('value');
        $property->setAccessible(true);

        $expected = 'btn btn-primary';

        $property->setValue($mock, $expected);

        $this->assertEquals($expected, $mock->getValue());
    }

    public function testJsonEncode()
    {
        $mock = $this->getMockBuilder(Attribute::class)
                ->setMethods(['getValue'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getValue')
                ->willReturn('btn btn-primary');

        $expected = '"btn btn-primary"';

        $this->assertEquals($expected, json_encode($mock));
    }

    public function testSetNameWithException()
    {
        $mock = $this->getMockBuilder(Attribute::class)
                ->setMethods(['setValue']) // Without this, an exception would not been thrown.
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(InvalidArgumentException::class);

        $mock->setName((new stdClass()));
    }

    public function testSetNameEmptyWithException()
    {
        $mock = $this->getMockBuilder(Attribute::class)
                ->setMethods(['setValue']) // Without this, an exception would not been thrown.
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(InvalidArgumentException::class);

        $mock->setName('');
    }

    public function testSetValueWithException()
    {
        $mock = $this->getMockBuilder(Attribute::class)
                ->setMethods(['setName']) // Without this, an exception would not been thrown.
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(InvalidArgumentException::class);

        $mock->setValue((new stdClass()));
    }

    public function testToString()
    {
        $mock = $this->getMockBuilder(Attribute::class)
                ->setMethods(['getName', 'getValue'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getName')
                ->willReturn('class');
        $mock->expects($this->once())
                ->method('getValue')
                ->willReturn('btn btn-primary');


        $actual = (string) $mock;
        $expected = 'class="btn btn-primary"';

        $this->assertEquals($expected, $actual);
    }

    public function testToStringNoValue()
    {
        $mock = $this->getMockBuilder(Attribute::class)
                ->setMethods(['getName', 'getValue'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getName')
                ->willReturn('required');
        $mock->expects($this->once())
                ->method('getValue')
                ->willReturn('');


        $actual = (string) $mock;
        $expected = 'required';

        $this->assertEquals($expected, $actual);
    }

}
