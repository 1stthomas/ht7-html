<?php

namespace Ht7\Base\Tests\Integration\Widgets;

use \BadMethodCallException;
use \InvalidArgumentException;
use \stdClass;
use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Node;
use \Ht7\Html\Tag;
use \Ht7\Html\Lists\AttributeList;
use \Ht7\Html\Lists\NodeList;
use \Ht7\Html\Widgets\AbstractWidgetController;
use \Ht7\Html\Widgets\AbstractWidgetModel;
use \Ht7\Html\Widgets\AbstractWidgetView;

class AbstractWidgetControllerTest extends TestCase
{

    public function testJsonSerialize()
    {
        $nL = new NodeList(['testtest', 1234, ['tag' => 'span']]);

        $view = $this->getMockBuilder(AbstractWidgetView::class)
                ->disableOriginalConstructor()
                ->setMethods(['getIsTransformed', 'getItemList'])
                ->getMockForAbstractClass();

        $view->expects($this->once())
                ->method('getIsTransformed')
                ->willReturn(true);
        $view->expects($this->once())
                ->method('getItemList')
                ->willReturn($nL);

        $mock = $this->getMockBuilder(AbstractWidgetController::class)
                ->disableOriginalConstructor()
                ->setMethods(['getView'])
                ->getMockForAbstractClass();

        $mock->expects($this->once())
                ->method('getView')
                ->willReturn($view);

        $actual = json_encode($mock);

        $this->assertStringContainsString('testtest', $actual);
        $this->assertStringContainsString('1234', $actual);
        $this->assertStringContainsString('"tag":"span"', $actual);
    }

    public function testLifeCycle()
    {
        $model = $this->getMockBuilder(AbstractWidgetModel::class)
                ->setConstructorArgs([['rendered string from view.']])
                ->getMockForAbstractClass();

        $view = $this->getMockBuilder(AbstractWidgetView::class)
                ->setMethods(['transform'])
                ->setConstructorArgs([$model])
                ->getMockForAbstractClass();

        $reflectedClass = new \ReflectionClass(AbstractWidgetView::class);
        $property = $reflectedClass->getProperty('itemList');
        $property->setAccessible(true);

        $view->expects($this->once())
                ->method('transform')
                ->willReturnCallback(function() use ($property, $view) {
                    $property->setValue($view, $view->getModel()->getData()[0]);
                    $view->setIsTransformed(true);
                });

        $mock = $this->getMockBuilder(AbstractWidgetController::class)
                ->setConstructorArgs([$model, $view])
                ->getMockForAbstractClass();

        $this->assertEquals($model, $mock->getModel());
        $this->assertEquals($view, $mock->getView());

        $this->assertStringContainsString('from view', (string) $mock->render());

        $mock->getView()->reset();

        $this->assertFalse($mock->getView()->getIsTransformed());
    }

}
