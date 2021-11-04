<?php

namespace Ht7\Html\Tests\Unit\Widgets\Wrapper;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Widgets\Wrapper\WrapperModel;

class WrapperModelTest extends TestCase
{

    protected $markup = ['tag' => 'button'];
    protected $items = [
        'item-1' => 'content items 1',
        'item-2' => 'content items 2',
    ];
    protected $callback = ['method' => 'testMethod'];

    public function testConstructorWith()
    {
        $className = WrapperModel::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setMarkup', 'setItems', 'setCallback'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setMarkup')
                ->with($this->equalTo($this->markup));
        $mock->expects($this->once())
                ->method('setItems')
                ->with($this->equalTo($this->items));
        $mock->expects($this->once())
                ->method('setCallback')
                ->with($this->equalTo($this->callback));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $this->markup, $this->items, $this->callback);
    }

    public function testAddItem()
    {
        $className = WrapperModel::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setCallback'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, []);

        $mock->addItem('item-11', new \Ht7\Html\Tag('span'));
        $mock->addItem('item-12', 'string content');
        $mock->addItem('item-13', 123.45);

        $actual = $property->getValue($mock);

        $this->assertArrayHasKey('item-11', $actual);
        $this->assertArrayHasKey('item-12', $actual);
        $this->assertArrayHasKey('item-13', $actual);
        $this->assertContains('string content', $actual);
        $this->assertContains(123.45, $actual);

        $mock->addItem('item-13', 123);

        $this->assertContains(123, $property->getValue($mock));
    }

    public function testGetCallback()
    {
        $className = WrapperModel::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setCallback'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('callback');
        $property->setAccessible(true);
        $property->setValue($mock, $this->callback);

        $actual = $mock->getCallback();

        $this->assertArrayHasKey('method', $actual);
        $this->assertContains('testMethod', $actual);
    }

    public function testGetItem()
    {
        $className = WrapperModel::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setCallback'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, $this->items);

        $actual = $mock->getItem('item-2');
        $this->assertStringContainsString($this->items['item-2'], $actual);

        $this->expectException(\InvalidArgumentException::class);

        $mock->getItem('item-3');
    }

    public function testGetItems()
    {
        $className = WrapperModel::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setCallback'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, $this->items);

        $actual = $mock->getItems();

        $this->assertArrayHasKey('item-1', $actual);
        $this->assertContains('content items 1', $actual);
        $this->assertArrayHasKey('item-2', $actual);
        $this->assertContains('content items 2', $actual);
    }

    public function testGetMarkup()
    {
        $className = WrapperModel::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setCallback'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('markup');
        $property->setAccessible(true);
        $property->setValue($mock, $this->markup);

        $actual = $mock->getMarkup();

        $this->assertArrayHasKey('tag', $actual);
        $this->assertContains('button', $actual);
    }

    public function testSetCallback()
    {
        $className = WrapperModel::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getCallback'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('callback');
        $property->setAccessible(true);
        $property->setValue($mock, []);

        $mock->setCallback($this->callback);

        $actual = $property->getValue($mock);

        $this->assertArrayHasKey('method', $actual);
        $this->assertContains('testMethod', $actual);
    }

    public function testSetItems()
    {
        $className = WrapperModel::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getCallback'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, []);

        $mock->setItems($this->items);

        $actual = $property->getValue($mock);

        $this->assertArrayHasKey('item-1', $actual);
        $this->assertArrayHasKey('item-2', $actual);
        $this->assertContains('content items 1', $actual);
        $this->assertContains('content items 2', $actual);
    }

    public function testSetMarkup()
    {
        $className = WrapperModel::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getCallback'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('markup');
        $property->setAccessible(true);
        $property->setValue($mock, []);

        $mock->setMarkup($this->markup);

        $actual = $property->getValue($mock);

        $this->assertArrayHasKey('tag', $actual);
        $this->assertContains('button', $actual);
    }

    public function testReset()
    {
        $className = WrapperModel::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setItems'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('setItems')
                ->with($this->equalTo([]));

        $mock->reset();
    }

}
