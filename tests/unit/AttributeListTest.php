<?php

namespace Ht7\Base\Tests;

use \InvalidArgumentException;
use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Attribute;
use \Ht7\Html\Lists\AttributeList;

/**
 * Test class for the SelfClosing class.
 *
 * @author      Thomas Pluess
 * @since       0.0.1
 * @version     0.0.1
 * @copyright (c) 2019, Thomas Pluess
 */
class AttributeListTest extends TestCase
{

    public function testConstructor()
    {
        $className = AttributeList::class;
        $items = ['class' => 'btn', 'id' => 'test-123'];

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
        $className = AttributeList::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['jsonSerialize'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);

        $attr1 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $attr1->expects($this->once())
                ->method('getHash')
                ->willReturn('class');
        $mock->add($attr1);

        $this->assertCount(1, $property->getValue($mock));

        $attr2 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $attr2->expects($this->once())
                ->method('getHash')
                ->willReturn('id');
        $mock->add($attr2);

        $this->assertCount(2, $property->getValue($mock));

        $attr3 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $attr3->expects($this->once())
                ->method('getHash')
                ->willReturn('class');
        $mock->add($attr3);

        $this->assertCount(2, $property->getValue($mock));

        $this->expectException(InvalidArgumentException::class);

        $mock->add(['class' => 'btn']);
    }

    public function testAddPlain()
    {
        $name = 'class';
        $value = 'btn btn-primary';

        $mock = $this->getMockBuilder(AttributeList::class)
                ->setMethods(['add'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('add')
                ->with((new Attribute($name, $value)));

        $mock->addPlain($name, $value);
    }

    public function testHasByValue()
    {
        $className = AttributeList::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['jsonSerialize'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);

        $attr1 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['getValue'])
                ->disableOriginalConstructor()
                ->getMock();
        $attr1->expects($this->any())
                ->method('getValue')
                ->willReturn('btn btn-primary');

        $attr2 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['getValue'])
                ->disableOriginalConstructor()
                ->getMock();
        $attr2->expects($this->any())
                ->method('getValue')
                ->willReturn('test-123');

        $attr3 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['getName', 'getValue'])
                ->disableOriginalConstructor()
                ->getMock();
        $attr3->expects($this->any())
                ->method('getValue')
                ->willReturn('');
        $attr3->expects($this->any())
                ->method('getName')
                ->willReturn('required');

        $property->setValue($mock, [$attr1, $attr2, $attr3]);

        $this->assertTrue($mock->hasByValue('test-123'));
        $this->assertFalse($mock->hasByValue('test-12'));
        $this->assertTrue($mock->hasByValue('btn btn-primary'));
        $this->assertFalse($mock->hasByValue('btn'));
        $this->assertTrue($mock->hasByValue('required'));
    }

    public function testJsonEncode()
    {
        $className = AttributeList::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['add'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $divider = $reflectedClass->getProperty('divider');
        $divider->setAccessible(true);
        $divider->setValue($mock, ' ');

        $attr1 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['jsonSerialize'])
                ->disableOriginalConstructor()
                ->getMock();
        $attr1->expects($this->once())
                ->method('jsonSerialize')
                ->willReturn('btn btn-primary');

        $attr2 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['jsonSerialize'])
                ->disableOriginalConstructor()
                ->getMock();
        $attr2->expects($this->once())
                ->method('jsonSerialize')
                ->willReturn('test-123');

        $attr3 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['jsonSerialize'])
                ->disableOriginalConstructor()
                ->getMock();
        $attr3->expects($this->once())
                ->method('jsonSerialize')
                ->willReturn('');

        $attrs = [
            'class' => $attr1,
            'id' => $attr2,
            'required' => $attr3
        ];

        $expected = [
            'class' => 'btn btn-primary',
            'id' => 'test-123',
            'required' => ''
        ];

        $property->setValue($mock, $attrs);

        $this->assertEquals($expected, json_decode(json_encode($mock), JSON_OBJECT_AS_ARRAY));
    }

    public function testLoad()
    {
        $className = AttributeList::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['add', 'addPlain'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);

        $attr1 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $attr2 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->exactly(2))
                ->method('add')
                ->withConsecutive([$attr1], [$attr2]);
        $mock->expects($this->exactly(3))
                ->method('addPlain')
                ->withConsecutive(['data-url', 'ch.ch'], ['disabled', ''], ['required', '']);

        $attrs = [
            $attr1,
            'data-url' => 'ch.ch',
            $attr2,
            'disabled',
            'required' => ''
        ];

        $mock->load($attrs);
    }

    public function testToString()
    {
        $className = AttributeList::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['add'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $divider = $reflectedClass->getProperty('divider');
        $divider->setAccessible(true);
        $divider->setValue($mock, ' ');

        $attr1 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['__toString'])
                ->disableOriginalConstructor()
                ->getMock();
        $attr1->expects($this->once())
                ->method('__toString')
                ->willReturn('class="btn btn-primary"');

        $attr2 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['__toString'])
                ->disableOriginalConstructor()
                ->getMock();
        $attr2->expects($this->once())
                ->method('__toString')
                ->willReturn('id="test-123"');

        $attr3 = $this->getMockBuilder(Attribute::class)
                ->setMethods(['__toString'])
                ->disableOriginalConstructor()
                ->getMock();
        $attr3->expects($this->once())
                ->method('__toString')
                ->willReturn('required');

        $attrs = [
            'class' => $attr1,
            'id' => $attr2,
            'required' => $attr3
        ];

        $property->setValue($mock, $attrs);

        $expected = 'class="btn btn-primary" id="test-123" required';

        $this->assertEquals($expected, ((string) $mock));
    }

}
