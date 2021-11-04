<?php

namespace Ht7\Html\Tests\Unit\Widgets;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Lists\NodeList;
use \Ht7\Html\Widgets\AbstractWidgetController;
use \Ht7\Html\Widgets\AbstractWidgetModel;
use \Ht7\Html\Widgets\AbstractWidgetView;

class AbstractWidgetControllerTest extends TestCase
{

    public function testConstructor()
    {
        // see: http://miljar.github.io/blog/2013/12/20/phpunit-testing-the-constructor/
        $className = AbstractWidgetController::class;
        $model = $this->getMockForAbstractClass(AbstractWidgetModel::class);
        $view = $this->getMockBuilder(AbstractWidgetView::class)
                ->setMethods(['setModel'])
                ->getMockForAbstractClass();

        $view->expects($this->once())
                ->method('setModel')
                ->with($this->equalTo($model));

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setModel', 'setView'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setModel')
                ->with($this->equalTo($model));
        $mock->expects($this->once())
                ->method('setView')
                ->with($this->equalTo($view));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $model, $view);
    }

    public function testGetModel()
    {
        $className = AbstractWidgetController::class;

        $model = $this->getMockForAbstractClass(AbstractWidgetModel::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('model');
        $property->setAccessible(true);

        $property->setValue($mock, $model);

        $this->assertEquals($model, $mock->getModel());
    }

    public function testGetView()
    {
        $className = AbstractWidgetController::class;

        $view = $this->getMockForAbstractClass(AbstractWidgetView::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setView'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('view');
        $property->setAccessible(true);

        $property->setValue($mock, $view);

        $this->assertEquals($view, $mock->getView());
    }

    public function testJsonSerialize()
    {
        $className = AbstractWidgetController::class;

        $iL = $this->getMockBuilder(NodeList::class)
                ->setMethods(['jsonSerialize'])
                ->disableOriginalConstructor()
                ->getMock();
        $iL->expects($this->once())
                ->method('jsonSerialize')
                ->willReturn('{"test":"return value"}');

        $view = $this->getMockBuilder(AbstractWidgetView::class)
                ->setMethods(['render'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();
        $view->expects($this->once())
                ->method('render')
                ->willReturn($iL);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getView'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getView')
                ->willReturn($view);

        $this->assertStringContainsString('return value', json_encode($mock));
    }

    public function testReset()
    {
        $className = AbstractWidgetController::class;

        $model = $this->getMockBuilder(AbstractWidgetModel::class)
                ->setMethods(['reset'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();
        $model->expects($this->once())
                ->method('reset');
        $view = $this->getMockBuilder(AbstractWidgetView::class)
                ->setMethods(['reset'])
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();
        $view->expects($this->once())
                ->method('reset');

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getModel', 'getView'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getModel')
                ->willReturn($model);
        $mock->expects($this->once())
                ->method('getView')
                ->willReturn($view);

        $mock->reset();
    }

    public function testSetModel()
    {
        $className = AbstractWidgetController::class;

        $model = $this->getMockForAbstractClass(AbstractWidgetModel::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setModel($model);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('model');
        $property->setAccessible(true);

        $this->assertEquals($model, $property->getValue($mock));
    }

    public function testSetView()
    {
        $className = AbstractWidgetController::class;

        $view = $this->getMockForAbstractClass(AbstractWidgetView::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setView($view);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('view');
        $property->setAccessible(true);

        $this->assertEquals($view, $property->getValue($mock));
    }

    public function testToString()
    {
        $expected = 'rendered string.';

        $mock = $this->getMockBuilder(AbstractWidgetController::class)
                ->setMethods(['getView'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getView')
                ->willReturn($expected);

        $this->assertEquals($expected, (string) $mock);
    }

}
