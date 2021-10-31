<?php

namespace Ht7\Html\Tests\Unit\Widgets\Wrapper;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Widgets\Wrapper\Wrapper;
use \Ht7\Html\Widgets\Wrapper\WrapperModel;
use \Ht7\Html\Widgets\Wrapper\WrapperView;

class WrapperTest extends TestCase
{

    public function testConstructorWith()
    {
        $className = Wrapper::class;
        $model = $this->getMockForAbstractClass(WrapperModel::class);
        $view = $this->getMockBuilder(WrapperView::class)
                ->setMethods(['setModel'])
                ->getMockForAbstractClass();

        $view->expects($this->once())
                ->method('setModel')
                ->with($this->equalTo($model));

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setModel', 'setView', 'setupModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setModel')
                ->with($this->equalTo($model));
        $mock->expects($this->once())
                ->method('setView')
                ->with($this->equalTo($view));
        $mock->expects($this->once())
                ->method('setupModel')
                ->with($this->equalTo($model));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $model, $view);
    }

    public function testConstructorWithout()
    {
        $className = Wrapper::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setModel', 'setView', 'setupModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setModel')
                ->with($this->isInstanceOf(WrapperModel::class));
        $mock->expects($this->once())
                ->method('setView')
                ->with($this->isInstanceOf(WrapperView::class));
        $mock->expects($this->once())
                ->method('setupModel')
                ->with($this->isInstanceOf(WrapperModel::class));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock);
    }

    public function testReplace()
    {
        $className = Wrapper::class;
        $id = 'test-123';
        $expected = 'replacing string.';

        $model = $this->getMockBuilder(WrapperModel::class)
                ->setMethods(['getItem'])
                ->getMockForAbstractClass();
        $model->expects($this->once())
                ->method('getItem')
                ->with($this->equalTo($id))
                ->willReturn($expected);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getModel')
                ->willReturn($model);

        $this->assertEquals($expected, $mock->replace(['id' => $id]));
    }

}
