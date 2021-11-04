<?php

namespace Ht7\Html\Tests\Unit\Widgets\Wrapper;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Tag;
use \Ht7\Html\Lists\NodeList;
use \Ht7\Html\Widgets\Wrapper\Wrapper;
use \Ht7\Html\Widgets\Wrapper\WrapperFactory;
use \Ht7\Html\Widgets\Wrapper\WrapperView;

class WrapperFactoryTest extends TestCase
{

    protected $factory;

    public function setUp()
    {
        parent::setUp();

        $this->factory = new WrapperFactory();
    }

    public function testConstructorWith()
    {
        $className = WrapperFactory::class;

        $mappings = [
            'bootstrap' => \Ht7\Html\Widgets\Wrapper\Markups\Bootstrap::class,
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setMappings'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setMappings')
                ->with($this->equalTo($mappings));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $mappings);
    }

    public function testConstructorWithout()
    {
        $className = WrapperFactory::class;

        $mappings = [
            'bootstrap' => \Ht7\Html\Widgets\Wrapper\Markups\Bootstrap::class,
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getDefaultMappings', 'setMappings'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getDefaultMappings')
                ->willReturn($mappings);
        $mock->expects($this->once())
                ->method('setMappings')
                ->with($this->equalTo($mappings));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock);
    }

    public function testCreateWrapperPlain()
    {
        $wrapper1 = $this->factory->createWrapperPlain();
        $this->assertInstanceOf(Wrapper::class, $wrapper1);

        $wrapper2 = $this->factory->createWrapperPlain();
        $this->assertInstanceOf(Wrapper::class, $wrapper2);

        $this->assertNotSame($wrapper1, $wrapper2);
    }

    public function testGetDefaultMappings()
    {
        $mappings = $this->factory->getDefaultMappings();

        $this->assertIsArray($mappings);
        $this->assertArrayHasKey('bootstrap', $mappings);
    }

    public function testGetMappings()
    {
        $className = WrapperFactory::class;

        $mappings = [
            'test' => '\Ht7\Test\Class',
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setMappings'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('mappings');
        $property->setAccessible(true);
        $property->setValue($mock, $mappings);

        $actual = $mock->getMapping('test');

        $this->assertEquals($mappings['test'], $actual);
    }

    public function testReset()
    {
        $className = WrapperFactory::class;

        $mappings = [
            'bootstrap3' => \Ht7\Html\Widgets\Wrapper\Markups\Bootstrap::class,
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setMappings', 'getDefaultMappings'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getDefaultMappings')
                ->willReturn($mappings);
        $mock->expects($this->once())
                ->method('setMappings')
                ->with($this->equalTo($mappings));

        $mock->reset();
    }

    public function testCreate()
    {
        $className = WrapperFactory::class;

        $markup = [
            ['tag' => 'span'],
        ];

        $model = $this->getMockBuilder(Wrapper::class)
                ->setMethods(['setMarkup'])
                ->getMock();
        $model->expects($this->once())
                ->method('setMarkup')
                ->with($this->equalTo($markup));

        $controller = $this->getMockBuilder(Wrapper::class)
                ->setMethods(['getModel'])
                ->getMock();
        $controller->expects($this->once())
                ->method('getModel')
                ->willReturn($model);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['createWrapperPlain'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('createWrapperPlain')
                ->willReturn($controller);

        $actual = $mock->create($markup);

        $this->assertInstanceOf(Wrapper::class, $actual);
        $this->assertEquals($controller, $actual);
    }

    public function testCreateWithItems()
    {
        $className = WrapperFactory::class;

        $markup = [
            ['tag' => 'span'],
        ];
        $items = ['123' => 'text 1', '456' => 'text 2'];

        $model = $this->getMockBuilder(Wrapper::class)
                ->setMethods(['setMarkup', 'setItems'])
                ->getMock();
        $model->expects($this->once())
                ->method('setMarkup')
                ->with($this->equalTo($markup));
        $model->expects($this->once())
                ->method('setItems')
                ->with($this->equalTo($items));

        $controller = $this->getMockBuilder(Wrapper::class)
                ->setMethods(['getModel'])
                ->getMock();
        $controller->expects($this->exactly(2))
                ->method('getModel')
                ->willReturn($model);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['createWrapperPlain'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('createWrapperPlain')
                ->willReturn($controller);

        $actual = $mock->create($markup, $items);

        $this->assertInstanceOf(Wrapper::class, $actual);
        $this->assertEquals($controller, $actual);
    }

    public function testCreateBootstrapRow()
    {
        $className = WrapperFactory::class;

        $markup = \Ht7\Html\Widgets\Wrapper\Markups\Bootstrap::ROW_SIMPLE_V3_12_6;
        $items = ['123' => 'item 1', '456' => 'item 2'];

        $wrapper = $this->getMockBuilder(Wrapper::class)
                ->setMethods([])
                ->getMock();

        $mock = $this->getMockBuilder($className)
                ->setMethods(['create', 'getMapping'])
                ->getMock();
        $mock->expects($this->once())
                ->method('getMapping')
                ->with('bootstrap')
                ->willReturn(\Ht7\Html\Widgets\Wrapper\Markups\Bootstrap::class);
        $mock->expects($this->once())
                ->method('create')
                ->with($this->equalTo($markup), $this->equalTo($items))
                ->willReturn($wrapper);

        $widget = $mock->createBootstrapRow($items);

        $this->assertInstanceOf(Wrapper::class, $widget);
    }

    public function testCreateBootstrapRowGetTag()
    {
        $className = WrapperFactory::class;

        $markup = \Ht7\Html\Widgets\Wrapper\Markups\Bootstrap::ROW_SIMPLE_V3_12_6;
        $items = ['123' => 'item 1', '456' => 'item 2'];

        $tag = $this->getMockBuilder(Tag::class)
                ->setMethods([])
                ->getMock();
        $nL = $this->getMockBuilder(WrapperView::class)
                ->setMethods(['get'])
                ->getMock();
        $nL->expects($this->once())
                ->method('get')
                ->with($this->equalTo(0))
                ->willReturn($tag);
        $view = $this->getMockBuilder(WrapperView::class)
                ->setMethods(['render'])
                ->getMock();
        $view->expects($this->once())
                ->method('render')
                ->willReturn($nL);
        $wrapper = $this->getMockBuilder(Wrapper::class)
                ->setMethods(['getView'])
                ->getMock();
        $wrapper->expects($this->once())
                ->method('getView')
                ->willReturn($view);

        $mock = $this->getMockBuilder($className)
//                ->setMethods(['create', 'getMapping', 'getView'])
                ->setMethods(['create', 'getMapping'])
                ->getMock();
        $mock->expects($this->once())
                ->method('getMapping')
                ->with('bootstrap')
                ->willReturn(\Ht7\Html\Widgets\Wrapper\Markups\Bootstrap::class);
        $mock->expects($this->once())
                ->method('create')
                ->with($this->equalTo($markup), $this->equalTo($items))
                ->willReturn($wrapper);

        $widget = $mock->createBootstrapRow($items, 'ROW_SIMPLE_V3_12_6', false);

        $this->assertInstanceOf(Tag::class, $widget);
        $this->assertEquals($tag, $widget);
    }

    public function testCreateBootstrapRowWithRowDef()
    {
        $className = WrapperFactory::class;

        $markup = \Ht7\Html\Widgets\Wrapper\Markups\Bootstrap::ROW_SIMPLE_V3_12_6_4;
        $items = ['123' => 'item 1', '456' => 'item 2'];

        $wrapper = $this->getMockBuilder(Wrapper::class)
                ->setMethods([])
                ->getMock();

        $mock = $this->getMockBuilder($className)
                ->setMethods(['create', 'getMapping'])
                ->getMock();
        $mock->expects($this->once())
                ->method('getMapping')
                ->with('bootstrap')
                ->willReturn(\Ht7\Html\Widgets\Wrapper\Markups\Bootstrap::class);
        $mock->expects($this->once())
                ->method('create')
                ->with($this->equalTo($markup), $this->equalTo($items))
                ->willReturn($wrapper);

        $widget = $mock->createBootstrapRow($items, 'ROW_SIMPLE_V3_12_6_4');

        $this->assertInstanceOf(Wrapper::class, $widget);
    }

}
