<?php

namespace Ht7\Html\Tests\Unit\Models;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Models\CallbackWithInstanceModel;

class CallbackWithInstanceModelTest extends TestCase
{

    public function testGetInstance()
    {
        $model = new CallbackWithInstanceModel($this, 'callbackTest01');

        $this->assertEquals($this, $model->getInstance());
    }

    public function testJsonSerialize()
    {
        $method = 'testMethod';
        $param = ['test' => 'test123'];
        $type = 'callback';

        $mock = $this->getMockBuilder(CallbackWithInstanceModel::class)
                ->setMethods(['getMethod', 'getInstance', 'getParameters'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->exactly(2))
                ->method('getMethod')
                ->willReturn($method);
        $mock->expects($this->exactly(2))
                ->method('getInstance')
                ->willReturn($this);
        $mock->expects($this->exactly(2))
                ->method('getParameters')
                ->willReturn($param);

        $actual = json_encode($mock);

        $expected1 = '"method":"testMethod"';
        $this->assertStringContainsString($expected1, $actual);
        $expected2 = '"parameters":{"test":"test123"}';
        $this->assertStringContainsString($expected2, $actual);
        $expected3 = '"type":"' . $type . '"';
        $this->assertStringContainsString($expected3, $actual);

        $actual2 = $mock->jsonSerialize();

        $this->assertArrayHasKey('instance', $actual2);
        $this->assertContains($this, $actual2);
    }

}
