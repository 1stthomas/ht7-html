<?php

namespace Ht7\Html\Tests\Unit\Models;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Models\CallbackWithCallableModel;

class CallbackWithCallableModelTest extends TestCase
{

    public function testGetCallable()
    {
        $callable = function() {
            return 'from callable';
        };

        $model = new CallbackWithCallableModel($callable);

        $this->assertEquals($callable, $model->getCallable());
    }

    public function testJsonSerialize()
    {
        $callable = function() {
            return 'callable stirng.';
        };
        $param = ['test' => 'test123'];
        $type = 'callback';

        $mock = $this->getMockBuilder(CallbackWithCallableModel::class)
                ->setMethods(['getCallable', 'getParameters'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->exactly(2))
                ->method('getCallable')
                ->willReturn($callable);
        $mock->expects($this->exactly(2))
                ->method('getParameters')
                ->willReturn($param);

        $actual = json_encode($mock);

        $expected2 = '"parameters":{"test":"test123"}';
        $this->assertStringContainsString($expected2, $actual);
        $expected3 = '"type":"' . $type . '"';
        $this->assertStringContainsString($expected3, $actual);

        $actual2 = $mock->jsonSerialize();

        $this->assertArrayHasKey('callable', $actual2);
        $this->assertContains($callable, $actual2);
    }

}
