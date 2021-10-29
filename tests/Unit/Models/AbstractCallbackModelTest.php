<?php

namespace Ht7\Html\Tests\Unit\Models;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Models\AbstractCallbackModel;

class AbstractCallbackModelTest extends TestCase
{

    public function testConstructor()
    {
        $params = ['test' => 12345];

        $mock = $this->getMockBuilder(AbstractCallbackModel::class)
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $reflectedClass = new \ReflectionClass(AbstractCallbackModel::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, ['test' => 12345]);

        $this->assertInstanceOf(AbstractCallbackModel::class, $mock);

        $property = $reflectedClass->getProperty('parameters');
        $property->setAccessible(true);

        $this->assertEquals($params, $property->getValue($mock));
    }

    public function testGetParameters()
    {
        $params = ['test' => 12345];

        $mock = $this->getMockBuilder(AbstractCallbackModel::class)
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $reflectedClass = new \ReflectionClass(AbstractCallbackModel::class);
        $property = $reflectedClass->getProperty('parameters');
        $property->setAccessible(true);
        $property->setValue($mock, $params);

        $this->assertEquals($params, $mock->getParameters());
    }

    public function testJsonSerialize()
    {
        $mock = $this->getMockBuilder(AbstractCallbackModel::class)
                ->setMethods(['getParameters'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $mock->expects($this->once())
                ->method('getParameters')
                ->willReturn(['test' => 12345]);

        $haystack = json_encode($mock);
        $expected1 = '"parameters":{"test":12345}';
        $expected2 = '"type":"callback"';

        $this->assertStringStartsWith('{', $haystack);
        $this->assertStringEndsWith('}', $haystack);
        $this->assertStringContainsString($expected1, $haystack);
        $this->assertStringContainsString($expected2, $haystack);
    }

}
