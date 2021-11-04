<?php

namespace Ht7\Html\Tests\Unit;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Callback;
use \Ht7\Html\Replacer;

class ReplacerTest extends TestCase
{

    public function testConstructor()
    {
        $className = Replacer::class;
        $params = [
            'id' => 'id-123',
            'callback' => [
                'instance' => $this,
                'method' => 'testit',
            ],
            'namespace' => 'unit-test'
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setId', 'setCallback', 'setNamespace'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setId')
                ->with($this->equalTo($params['id']));
        $mock->expects($this->once())
                ->method('setCallback')
                ->with($this->equalTo($params['callback']));
        $mock->expects($this->once())
                ->method('setNamespace')
                ->with($this->equalTo($params['namespace']));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $params);
    }

    public function testConstructorWithException()
    {
        $className = Replacer::class;
        $params = [
            'callback' => [
                'instance' => $this,
                'method' => 'testit',
            ],
            'namespace' => 'unit-test'
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setId'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(\InvalidArgumentException::class);

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $params);
    }

    public function testGetCallback()
    {
        $className = Replacer::class;

        $callback = $this->getMockClass(Callback::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setCallback'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('callback');
        $property->setAccessible(true);
        $property->setValue($mock, $callback);

        $this->assertEquals($callback, $mock->getCallback());
    }

    public function testGetCallbackWithArray()
    {
        $className = Replacer::class;

        $callback = ['method' => 'test'];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setCallback'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('callback');
        $property->setAccessible(true);
        $property->setValue($mock, $callback);

        $this->assertInstanceOf(Callback::class, $mock->getCallback());
    }

    public function testGetId()
    {
        $className = Replacer::class;
        $id = 'id-12345';

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setId'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($mock, $id);

        $this->assertEquals($id, $mock->getId());
    }

    public function testGetNamespace()
    {
        $className = Replacer::class;

        $ns = 'test/replacer';

        $mock = $this->getMockBuilder($className)
                ->setMethods(['process'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('namespace');
        $property->setAccessible(true);
        $property->setValue($mock, $ns);

        $this->assertEquals($ns, $mock->getNamespace());
    }

    public function testJsonSerialize()
    {
        $className = Replacer::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getCallback'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('getCallback')
                ->willReturn('{"text":"from callback"}');

        $json = json_encode($mock);

        $this->assertStringContainsString('from callback', $json);
    }

    public function testSetCallbackWithCallback()
    {
        $className = Replacer::class;

        $cb = $this->createMock(Callback::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getId'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setCallback($cb);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('callback');
        $property->setAccessible(true);
        $callback = $property->getValue($mock);

        $this->assertEquals($cb, $callback);
    }

    public function testSetCallbackWithParameters()
    {
        $className = Replacer::class;

        $arr = [
            'method' => 'test',
            'parameters' => [
                'key' => 'value',
            ]
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getId'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('getId')
                ->willReturn('id-gen-123');

        $mock->setCallback($arr);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('callback');
        $property->setAccessible(true);
        $callback = $property->getValue($mock);

        $this->assertArrayHasKey('method', $callback);
        $this->assertArrayHasKey('parameters', $callback);
        $this->assertArrayHasKey('key', $callback['parameters']);
        $this->assertArrayHasKey('id', $callback['parameters']);
        $this->assertContains('id-gen-123', $callback['parameters']['id']);
    }

    public function testSetCallbackWithParametersEmpty()
    {
        $className = Replacer::class;

        $arr = [
            'method' => 'test',
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getId'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('getId')
                ->willReturn('id-gen-123');

        $mock->setCallback($arr);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('callback');
        $property->setAccessible(true);
        $callback = $property->getValue($mock);

        $this->assertArrayHasKey('method', $callback);
        $this->assertArrayHasKey('parameters', $callback);
        $this->assertArrayNotHasKey('key', $callback['parameters']);
        $this->assertArrayHasKey('id', $callback['parameters']);
        $this->assertContains('id-gen-123', $callback['parameters']['id']);
    }

    public function testSetCallbackEmptyArrayWithException()
    {
        $className = Replacer::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getId'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/not supported/');

        $mock->setCallback();
    }

    public function testSetCallbackStdclassWithException()
    {
        $className = Replacer::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getId'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/unsupported/i');

        $mock->setCallback(new \stdClass());
    }

    public function testSetContent()
    {
        $className = Replacer::class;
        $content = [];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['createModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setContent($content);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('content');
        $property->setAccessible(true);

        $this->assertEquals($content, $property->getValue($mock));
    }

    public function testSetId()
    {
        $className = Replacer::class;
        $id = 'id-123';

        $mock = $this->getMockBuilder($className)
                ->setMethods(['createModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setId($id);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('id');
        $property->setAccessible(true);

        $this->assertEquals($id, $property->getValue($mock));
    }

    public function testSetNamespace()
    {
        $className = Replacer::class;

        $ns = 'test/replacer';

        $mock = $this->getMockBuilder($className)
                ->setMethods(['createModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setNamespace($ns);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('namespace');
        $property->setAccessible(true);

        $this->assertEquals($ns, $property->getValue($mock));
    }

    public function testToString()
    {
        $className = Replacer::class;

        $callback = 123.45;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getCallback'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('getCallback')
                ->willReturn($callback);

        $this->assertEquals('123.45', (string) $mock);
    }

}
