<?php

namespace Ht7\Base\Tests\Unit\Lists;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Lists\NodeList;
use \Ht7\Html\Utilities\ImporterArray;

/**
 * @covers \Ht7\Html\Lists\NodeList
 * @uses \Ht7\Html\Utilities\ImporterArray
 */
final class NodeListTest extends TestCase
{

    public function testConstructor()
    {
        $className = NodeList::class;
        $items = ['test text'];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['load'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('load')
                ->with($this->equalTo($items));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $items);
    }

    public function testAdd()
    {
        $value = 'test text';

        $importer = $this->getMockBuilder(ImporterArray::class)
                ->disableOriginalConstructor()
                ->setMethods(['import'])
                ->getMock();

        $importer->expects($this->once())
                ->method('import')
                ->with($value)
                ->willReturn($value);

        ImporterArray::setInstance($importer, ImporterArray::class);

        $mock = $this->getMockBuilder(NodeList::class)
                ->disableOriginalConstructor()
                ->setMethods(['load'])
                ->getMock();

        $reflectedClass = new \ReflectionClass(NodeList::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, []);

        $this->assertInstanceOf(NodeList::class, $mock->add($value));
    }

    public function testJsonEncode()
    {
        $mock = $this->getMockBuilder(NodeList::class)
                ->setMethods(['getAll'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getAll')
                ->willReturn(['test 1', 'test 2']);

        $expected = '["test 1","test 2"]';

        $this->assertEquals($expected, json_encode($mock));
    }

}
