<?php

namespace Ht7\Html\Tests\Unit;

use \PHPUnit\Framework\TestCase;
use \Ht7\Base\Exceptions\InvalidDatatypeException;
use \Ht7\Html\Attribute;
use \Ht7\Html\Lists\AttributeList;
use \Ht7\Html\Lists\NodeList;

class CanRenderListTest extends TestCase
{

    public function testGetDivider()
    {
        $className = NodeList::class;
        $expected = ' | ';

        $mock = $this->getMockBuilder($className)
                ->setMethods(['jsonSerialize'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('divider');
        $property->setAccessible(true);

        $property->setValue($mock, $expected);

        $this->assertEquals($expected, $mock->getDivider());
    }

    public function testSetDivider()
    {
        $className = NodeList::class;
        $expected = ' | ';

        $mock = $this->getMockBuilder($className)
                ->setMethods(['jsonSerialize'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('divider');
        $property->setAccessible(true);

        $mock->setDivider($expected);

        $this->assertEquals($expected, $property->getValue($mock));

        $this->expectException(InvalidDatatypeException::class);

        $mock->setDivider((new NodeList()));
    }

    public function testToString()
    {
        $expected = 'test | text | end';

        $mock = $this->getMockBuilder(NodeList::class)
                ->setMethods(['getAll', 'getDivider'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getAll')
                ->willReturn(['test', 'text', 'end']);
        $mock->expects($this->once())
                ->method('getDivider')
                ->willReturn(' | ');

        $this->assertEquals($expected, ((string) $mock));
    }

    public function testToStringSorted()
    {
        $expected = 'class="btn" data-url="/test1/test2" id="test-123"';

        $attr1 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['__toString'])
                ->disableOriginalConstructor()
                ->getMock();
        $attr1->expects($this->once())
                ->method('__toString')
                ->willReturn('id="test-123"');
        $attr2 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['__toString'])
                ->disableOriginalConstructor()
                ->getMock();
        $attr2->expects($this->once())
                ->method('__toString')
                ->willReturn('class="btn"');
        $attr3 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['__toString'])
                ->disableOriginalConstructor()
                ->getMock();
        $attr3->expects($this->once())
                ->method('__toString')
                ->willReturn('data-url="/test1/test2"');

        $raw = [
            'id' => $attr1,
            'class' => $attr2,
            'data-url' => $attr3
        ];

        // Hier brauchts eine NodeList!!! Die AttributeList toString muss anderswo!
        $mock = $this->getMockBuilder(AttributeList::class)
                ->setMethods(['getAll', 'getDivider'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getAll')
                ->willReturn($raw);
        $mock->expects($this->once())
                ->method('getDivider')
                ->willReturn(' ');

//        print_r($mock->getAll());

        $this->assertEquals($expected, ((string) $mock));
    }

}
