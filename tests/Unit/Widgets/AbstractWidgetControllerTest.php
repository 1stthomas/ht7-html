<?php

namespace Ht7\Html\Tests\Unit\Widgets;

use \PHPUnit\Framework\TestCase;
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
        $view = $this->getMockForAbstractClass(AbstractWidgetView::class);

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

}
