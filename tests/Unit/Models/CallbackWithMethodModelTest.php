<?php

namespace Ht7\Html\Tests\Unit\Models;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Models\CallbackWithMethodModel;

class CallbackWithMethodModelTest extends TestCase
{

    public function testGetInstance()
    {
        $model = new CallbackWithMethodModel('callbackTest01');

        $this->assertEquals('callbackTest01', $model->getMethod());
    }

    public function testJsonSerialize()
    {
        $method = 'testMethod';
        $param = ['test' => 'test123'];
        $type = 'callback';

        $mock = $this->getMockBuilder(CallbackWithMethodModel::class)
                ->setMethods(['getMethod', 'getParameters'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getMethod')
                ->willReturn($method);
        $mock->expects($this->once())
                ->method('getParameters')
                ->willReturn($param);

        $actual = json_encode($mock);

        $expected1 = '"method":"testMethod"';
        $this->assertStringContainsString($expected1, $actual);
        $expected2 = '"parameters":{"test":"test123"}';
        $this->assertStringContainsString($expected2, $actual);
        $expected3 = '"type":"' . $type . '"';
        $this->assertStringContainsString($expected3, $actual);
    }

}
