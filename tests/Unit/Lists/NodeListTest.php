<?php

namespace Ht7\Base\Tests\Unit;

use \PHPUnit\Framework\TestCase;
use \Ht7\Base\Exceptions\InvalidDatatypeException;
use \Ht7\Html\Attribute;
use \Ht7\Html\Tag;
use \Ht7\Html\Text;
use \Ht7\Html\Lists\NodeList;

/**
 * Test class for the NodeList class.
 *
 * @author      Thomas Pluess
 * @since       0.0.1
 * @version     0.0.1
 * @copyright (c) 2019, Thomas Pluess
 */
class NodeListTest extends TestCase
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
        $className = NodeList::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setTagName', 'setContent', 'setAttributes'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);

        $text = $this->createMock(Text::class);
        $mock->add($text);

        $this->assertCount(1, $property->getValue($mock));

        $arr = ['tag' => 'div'];
        $mock->add($arr);

        $this->assertCount(2, $property->getValue($mock));

        $plain = 'text plain';
        $mock->add($plain);

        $this->assertCount(3, $property->getValue($mock));

        $this->expectException(InvalidDatatypeException::class);

        $mock->add((new NodeList()));
    }

    public function testJsonEncode()
    {
        $node1 = $this->getMockBuilder(Text::class)
                ->setMethods(['jsonSerialize'])
                ->disableOriginalConstructor()
                ->getMock();
        $node1->expects($this->once())
                ->method('jsonSerialize')
                ->willReturn('test text 1');

        $node2 = $this->getMockBuilder(Tag::class)
                ->setMethods(['jsonSerialize'])
                ->disableOriginalConstructor()
                ->getMock();
        $node2->expects($this->once())
                ->method('jsonSerialize')
                ->willReturn(['attributes' => ['class' => 'btn'], 'content' => ['inner text'], 'tag' => 'div']);

        $node3 = $this->getMockBuilder(Text::class)
                ->setMethods(['jsonSerialize'])
                ->disableOriginalConstructor()
                ->getMock();
        $node3->expects($this->once())
                ->method('jsonSerialize')
                ->willReturn('test text 2');

        $data = [$node1, $node2, $node3];
        $className = NodeList::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['load'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);

        $property->setValue($mock, $data);

        $expected = [
            'test text 1',
            [
                'attributes' => ['class' => 'btn'],
                'content' => ['inner text'],
                'tag' => 'div'
            ],
            'test text 2'
        ];

        $this->assertEquals($expected, json_decode(json_encode($mock), JSON_OBJECT_AS_ARRAY));
    }

}
