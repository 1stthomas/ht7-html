<?php

namespace Ht7\Html\Tests\Unit\Widgets;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Lists\NodeList;
use \Ht7\Html\Widgets\AbstractWidgetModel;
use \Ht7\Html\Widgets\AbstractWidgetView;

class AbstractWidgetViewTest extends TestCase
{

    public function testConstructor()
    {
        // see: http://miljar.github.io/blog/2013/12/20/phpunit-testing-the-constructor/
        $className = AbstractWidgetView::class;
        $model = $this->getMockForAbstractClass(AbstractWidgetModel::class);
        $iL = $this->createMock(NodeList::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getModel'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $mock->expects($this->once())
                ->method('setModel')
                ->with($this->equalTo($model));
        $mock->expects($this->once())
                ->method('setItemList')
                ->with($this->equalTo($iL));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $model, $iL);
        $isTransformed = $reflectedClass->getProperty('isTransformed');
        $isTransformed->setAccessible(true);

        $this->assertFalse($isTransformed->getValue($mock));
    }

    public function testConstructorNoModel()
    {
        // see: http://miljar.github.io/blog/2013/12/20/phpunit-testing-the-constructor/
        $className = AbstractWidgetView::class;
        $iL = $this->createMock(NodeList::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getModel'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $mock->expects($this->never())
                ->method('setModel');
        $mock->expects($this->once())
                ->method('setItemList')
                ->with($this->equalTo($iL));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, null, $iL);
        $isTransformed = $reflectedClass->getProperty('isTransformed');
        $isTransformed->setAccessible(true);

        $this->assertFalse($isTransformed->getValue($mock));
    }

    public function testConstructorNoList()
    {
        // see: http://miljar.github.io/blog/2013/12/20/phpunit-testing-the-constructor/
        $className = AbstractWidgetView::class;
        $model = $this->getMockForAbstractClass(AbstractWidgetModel::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getModel'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $mock->expects($this->once())
                ->method('setModel')
                ->with($this->equalTo($model));
        $mock->expects($this->once())
                ->method('setItemList')
                ->with($this->isInstanceOf(NodeList::class));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $model);
        $isTransformed = $reflectedClass->getProperty('isTransformed');
        $isTransformed->setAccessible(true);

        $this->assertFalse($isTransformed->getValue($mock));
    }

    public function testConstructorEmpty()
    {
        $className = AbstractWidgetView::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setModel', 'setItemList'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $mock->expects($this->never())
                ->method('setModel');
        $mock->expects($this->once())
                ->method('setItemList')
                ->with($this->isInstanceOf(NodeList::class));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock);
        $isTransformed = $reflectedClass->getProperty('isTransformed');
        $isTransformed->setAccessible(true);

        $this->assertFalse($isTransformed->getValue($mock));
    }

    public function testGetItemList()
    {
        $className = AbstractWidgetView::class;

        $iL = $this->createMock(NodeList::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setData'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('itemList');
        $property->setAccessible(true);

        $property->setValue($mock, $iL);

        $this->assertEquals($iL, $mock->getItemList());
    }

    public function testRender()
    {
        $className = AbstractWidgetView::class;

        $iL = $this->createMock(NodeList::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getItemList', 'transform'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $mock->expects($this->once())
                ->method('transform');
        $mock->expects($this->once())
                ->method('getItemList')
                ->willReturn($iL);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('isTransformed');
        $property->setAccessible(true);

        $property->setValue($mock, false);

        $this->assertEquals($iL, $mock->render());
    }

    public function testRenderWithout()
    {
        $className = AbstractWidgetView::class;

        $iL = $this->createMock(NodeList::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getItemList', 'transform'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $mock->expects($this->never())
                ->method('transform');
        $mock->expects($this->once())
                ->method('getItemList')
                ->willReturn($iL);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('isTransformed');
        $property->setAccessible(true);

        $property->setValue($mock, true);

        $this->assertEquals($iL, $mock->render());
    }

    public function testReset()
    {
        $className = AbstractWidgetView::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setItemList'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $mock->expects($this->once())
                ->method('setItemList')
                ->with($this->isInstanceOf(NodeList::class));

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('isTransformed');
        $property->setAccessible(true);

        $property->setValue($mock, true);

        $mock->reset();

        $this->assertFalse($property->getValue($mock));
    }

    public function testResetWithout()
    {
        $className = AbstractWidgetView::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setItemList'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $mock->expects($this->never())
                ->method('setItemList');

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('isTransformed');
        $property->setAccessible(true);

        $property->setValue($mock, false);

        $mock->reset();

        $this->assertFalse($property->getValue($mock));
    }

    public function testSetItemList()
    {
        $className = AbstractWidgetView::class;

        $iL = $this->createMock(NodeList::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getItemList'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $mock->setItemList($iL);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('itemList');
        $property->setAccessible(true);

        $this->assertEquals($iL, $property->getValue($mock));
    }

    public function testSetModel()
    {
        $className = AbstractWidgetView::class;

        $model = $this->getMockForAbstractClass(AbstractWidgetModel::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setItemList'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $mock->setModel($model);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('model');
        $property->setAccessible(true);

        $this->assertEquals($model, $property->getValue($mock));
    }

    public function testToString()
    {
        $className = AbstractWidgetView::class;

        $model = $this->getMockForAbstractClass(AbstractWidgetModel::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['render'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

        $mock->expects($this->once())
                ->method('render')
                ->willReturn(12345);

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $model);

        $this->assertEquals('12345', (string) $mock);
    }

}
