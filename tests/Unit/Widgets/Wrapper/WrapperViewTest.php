<?php

namespace Ht7\Html\Tests\Unit\Widgets\Wrapper;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Tag;
use \Ht7\Html\Lists\NodeList;
use \Ht7\Html\Utilities\ImporterArray;
use \Ht7\Html\Models\ImporterArrayDefaultsModel;
use \Ht7\Html\Widgets\Wrapper\WrapperModel;
use \Ht7\Html\Widgets\Wrapper\WrapperView;

class WrapperViewTest extends TestCase
{

    public function testConstructorWith()
    {
        $className = WrapperView::class;

        $model = $this->createMock(WrapperModel::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setModel')
                ->with($this->equalTo($model));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $model);
    }

    public function testConstructorWithout()
    {
        $className = WrapperView::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setModel')
                ->with($this->isInstanceOf(WrapperModel::class));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock);
    }

    public function testTransform()
    {
        $className = WrapperView::class;

        $callback = [];
        $markup = [];

        $tag = $this->getMockBuilder(Tag::class)
                ->setMethods(['getTagName'])
                ->disableOriginalConstructor()
                ->getMock();
        $model = $this->getMockBuilder(WrapperModel::class)
                ->setMethods(['getCallback', 'getMarkup'])
                ->disableOriginalConstructor()
                ->getMock();
        $model->expects($this->once())
                ->method('getCallback')
                ->willReturn($callback);
        $model->expects($this->once())
                ->method('getMarkup')
                ->willReturn($markup);
        $defaults = $this->getMockBuilder(ImporterArrayDefaultsModel::class)
                ->setMethods(['setCallback'])
                ->disableOriginalConstructor()
                ->getMock();
        $defaults->expects($this->once())
                ->method('setCallback')
                ->with($this->equalTo($callback));
        $importer = $this->getMockBuilder(ImporterArray::class)
                ->setMethods(['getDefaults', 'import'])
                ->disableOriginalConstructor()
                ->getMock();
        $importer->expects($this->once())
                ->method('getDefaults')
                ->willReturn($defaults);
        $importer->expects($this->once())
                ->method('import')
                ->with($this->equalTo($markup))
                ->willReturn($tag);
        $nL = $this->getMockBuilder(NodeList::class)
                ->setMethods(['add'])
                ->getMock();
        $nL->expects($this->once())
                ->method('add')
                ->with($tag);

        ImporterArray::setInstance($importer, ImporterArray::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getModel', 'getItemList', 'setIsTransformed'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('getModel')
                ->willReturn($model);
        $mock->expects($this->once())
                ->method('getItemList')
                ->willReturn($nL);
        $mock->expects($this->once())
                ->method('setIsTransformed')
                ->with($this->equalTo(true));

        $reflectedClass = new \ReflectionClass($className);
        $method = $reflectedClass->getMethod('transform');
        $method->setAccessible(true);
        $method->invoke($mock);
    }

}
