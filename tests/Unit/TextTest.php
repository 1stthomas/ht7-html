<?php

namespace Ht7\Html\Tests\Unit;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Text;

class TextTest extends TestCase
{

    public function testConstructor()
    {
        $className = Text::class;
        $content = ['test text'];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setContent'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setContent')
                ->with($this->equalTo($content));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $content);
    }

    public function testGetContent()
    {
        $className = Text::class;
        $content = 'test text';

        $mock = $this->getMockBuilder($className)
                ->setMethods(['jsonSerialize'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('content');
        $property->setAccessible(true);

        $property->setValue($mock, $content);

        $this->assertEquals($content, $mock->getContent());
    }

    public function testJsonSerialize()
    {
        $className = Text::class;
        $content = 'test text';

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setContent'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('content');
        $property->setAccessible(true);

        $property->setValue($mock, $content);

        $expected = '"' . $content . '"';
        $actual = json_encode($mock);

        $this->assertEquals($expected, $actual);
    }

    public function testSetContent()
    {
        $className = Text::class;
        $content = 'test text';

        $mock = $this->getMockBuilder($className)
                ->setMethods(['jsonSerialize'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('content');
        $property->setAccessible(true);

        $mock->setContent($content);
        $this->assertEquals($content, $property->getValue($mock));

        $content2 = 123;
        $mock->setContent($content2);
        $this->assertEquals($content2, $property->getValue($mock));

        $content3 = 123.001;
        $mock->setContent($content3);
        $this->assertEquals($content3, $property->getValue($mock));
    }

    public function testSetContentWithException()
    {
        $mock = $this->getMockBuilder(Text::class)
                ->setMethods(['jsonSerialize'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(\InvalidArgumentException::class);

        $mock->setContent([]);
    }

    public function testToString()
    {
        $expected = 'test text.';

        $text = $this->getMockBuilder(Text::class)
                ->setMethods(['getContent'])
                ->disableOriginalConstructor()
                ->getMock();

        $text->expects($this->once())
                ->method('getContent')
                ->willReturn($expected);

        $this->assertEquals($expected, ((string) $text));
    }

}
