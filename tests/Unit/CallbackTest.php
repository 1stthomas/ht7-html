<?php

namespace Ht7\Html\Tests\Unit;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Callback;
use \Ht7\Html\Models\CallbackWithCallableModel;
use \Ht7\Html\Models\CallbackWithInstanceModel;
use \Ht7\Html\Models\CallbackWithMethodModel;

class CallbackTest extends TestCase
{

    public function callbackTest01()
    {
        return 'dies 123';
    }

    public function testConstructor()
    {
        $className = Callback::class;
        $content = [
            'method' => '',
            'instance' => $this
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setContent'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setContent')
                ->with($this->equalTo($content));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $content);
    }

    public function testCreateModelExceptionByString()
    {
        $className = Callback::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['createModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $method = $reflectedClass->getMethod('createModel');
        $method->setAccessible(true);

        $this->expectException(\TypeError::class);

        $method->invokeArgs($mock, ['testMethod']);
    }

    public function testCreateModelCallable()
    {
        $className = Callback::class;
        $content = [
            'type' => 'callback',
            'callable' => function() {
                return 'string from callable.';
            },
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['process'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $method = $reflectedClass->getMethod('createModel');
        $method->setAccessible(true);

        $method->invokeArgs($mock, [$content]);

        $property = $reflectedClass->getProperty('model');
        $property->setAccessible(true);

        $this->assertInstanceOf(CallbackWithCallableModel::class, $property->getValue($mock));
    }

    public function testCreateModelMethod()
    {
        $className = Callback::class;

        $content = [
            'type' => 'callback',
            'method' => 'testMethod',
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['process'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $method = $reflectedClass->getMethod('createModel');
        $method->setAccessible(true);

        $method->invokeArgs($mock, [$content]);

        $property = $reflectedClass->getProperty('model');
        $property->setAccessible(true);

        $this->assertInstanceOf(CallbackWithMethodModel::class, $property->getValue($mock));
    }

    public function testJsonSerialize()
    {
        $className = Callback::class;

        $return = '{"test":"test123"}';
        $expected = '"{\"test\":\"test123\"}"';

        $mock = $this->getMockBuilder($className)
                ->setMethods(['process'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('process')
                ->willReturn($return);

        $this->assertEquals($expected, json_encode($mock));
    }

    public function testProcessWithCallable()
    {
        $className = Callback::class;
        $callable = function() {
            return 'string from callable.';
        };

        $model = $this->createMock(CallbackWithCallableModel::class);
        $model->method('getCallable')
                ->willReturn($callable);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('getModel')
                ->willReturn($model);

        $reflectedClass = new \ReflectionClass($className);
        $method = $reflectedClass->getMethod('process');
        $method->setAccessible(true);

        $expected = 'string from callable.';
        $actual = $method->invokeArgs($mock, []);

        $this->assertEquals($expected, $actual);
    }

    public function testProcessWithInstance()
    {
        $className = Callback::class;

        $model = $this->createMock(CallbackWithInstanceModel::class);
        $model->method('getInstance')
                ->willReturn($this);
        $model->method('getMethod')
                ->willReturn('callbackTest01');

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('getModel')
                ->willReturn($model);

        $reflectedClass = new \ReflectionClass($className);
        $method = $reflectedClass->getMethod('process');
        $method->setAccessible(true);

        $expected = 'dies 123';
        $actual = $method->invokeArgs($mock, []);

        $this->assertEquals($expected, $actual);
    }

    public function testProcessWithMethod()
    {
        if (file_exists('./assets/functions/callbacks.php')) {
            include_once './assets/functions/callbacks.php';
        } else {
            throw new \BadMethodCallException('Missing callback functions file.');
        }

        $className = Callback::class;

        $model = $this->createMock(CallbackWithMethodModel::class);
        $model->method('getMethod')
                ->willReturn('callbackFunction01');

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('getModel')
                ->willReturn($model);

        $reflectedClass = new \ReflectionClass($className);
        $method = $reflectedClass->getMethod('process');
        $method->setAccessible(true);

        $expected = 'string from callback function.';
        $actual = $method->invokeArgs($mock, []);

        $this->assertEquals($expected, $actual);
    }

    public function testProcessExceptionByEmptyModel()
    {
        $className = Callback::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('getModel')
                ->willReturn(null);

        $reflectedClass = new \ReflectionClass($className);
        $method = $reflectedClass->getMethod('process');
        $method->setAccessible(true);

        $this->expectException(\BadMethodCallException::class);

        $method->invokeArgs($mock, []);
    }

    public function testProcessExceptionByUnsupportedType()
    {
        $className = Callback::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('getModel')
                ->willReturn(new \stdClass());

        $reflectedClass = new \ReflectionClass($className);
        $method = $reflectedClass->getMethod('process');
        $method->setAccessible(true);

        $this->expectException(\BadMethodCallException::class);

        $method->invokeArgs($mock, []);
    }

    public function testSetContentWithCallable()
    {
        $className = Callback::class;
        $content = function() {
            return 'some text';
        };

        $mock = $this->getMockBuilder($className)
                ->setMethods(['createModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('createModel')
                ->with($this->equalTo(['callable' => $content]));

        $mock->setContent($content);
    }

    public function testSetContentWithString()
    {
        $className = Callback::class;
        $content = 'methodOne';

        $mock = $this->getMockBuilder($className)
                ->setMethods(['createModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('createModel')
                ->with($this->equalTo(['method' => $content]));

        $mock->setContent($content);
    }

    public function testToString()
    {
        $expected = 'test text.';

        $text = $this->getMockBuilder(Callback::class)
                ->setMethods(['process'])
                ->disableOriginalConstructor()
                ->getMock();

        $text->expects($this->once())
                ->method('process')
                ->willReturn($expected);

        $this->assertEquals($expected, ((string) $text));
    }

}
