<?php

namespace Ht7\Html\Tests\Unit;

use \BadMethodCallException;
use \InvalidArgumentException;
use \stdClass;
use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Node;
use \Ht7\Html\Tag;
use \Ht7\Html\Iterators\PreOrderIterator;
use \Ht7\Html\Lists\AttributeList;
use \Ht7\Html\Lists\NodeList;

class TagTest extends TestCase
{

    public function testConstructor()
    {
        // see: http://miljar.github.io/blog/2013/12/20/phpunit-testing-the-constructor/
        $className = Tag::class;
        $tagName = 'span';
        $content = ['test text'];
        $attributes = ['class' => 'btn btn-primary'];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setTagName', 'setContent', 'setAttributes'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setTagName')
                ->with($this->equalTo($tagName));
        $mock->expects($this->once())
                ->method('setContent')
                ->with($this->equalTo($content));
        $mock->expects($this->once())
                ->method('setAttributes')
                ->with($this->equalTo($attributes));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $tagName, $content, $attributes);
    }

    public function testGetAttributes()
    {
        $tag1 = new Tag('div', ['bla']);

        $this->assertInstanceOf(AttributeList::class, $tag1->getAttributes());

        $tag2 = new Tag('div', ['bla'], ['class' => 'btn']);

        $this->assertInstanceOf(AttributeList::class, $tag2->getAttributes());
    }

    public function testGetContent()
    {
        $tag1 = new Tag('div');

        $this->assertInstanceOf(NodeList::class, $tag1->getContent());

        $tag2 = new Tag('div', ['bla']);

        $this->assertInstanceOf(NodeList::class, $tag2->getContent());
    }

    public function testGetIterator()
    {
        $tag1 = new Tag('div');

        $this->assertInstanceOf(PreOrderIterator::class, $tag1->getIterator());

        $tag2 = new Tag('div', ['bla']);

        $this->assertInstanceOf(PreOrderIterator::class, $tag2->getIterator());
    }

    public function testGetIteratorPreOrder()
    {
        $tag1 = new Tag('div');

        $this->assertInstanceOf(PreOrderIterator::class, $tag1->getIteratorPreOrder());

        $tag2 = new Tag('div', ['bla']);

        $this->assertInstanceOf(PreOrderIterator::class, $tag2->getIteratorPreOrder());
    }

    public function testJsonSerialize()
    {
        $nlMock = $this->createMock(NodeList::class);

        $nlMock->expects($this->once())
                ->method('jsonSerialize')
                ->willReturn(['text']);

        $alMock = $this->createMock(AttributeList::class);

        $alMock->expects($this->once())
                ->method('jsonSerialize')
                ->willReturn(['class' => 'btn btn-primary']);

        $mock = $this->getMockBuilder(Tag::class)
                ->setMethods(['getAttributes', 'getContent', 'getTagName'])
                ->getMock();

        $mock->expects($this->once())
                ->method('getAttributes')
                ->willReturn($alMock);
        $mock->expects($this->once())
                ->method('getContent')
                ->willReturn($nlMock);
        $mock->expects($this->once())
                ->method('getTagName')
                ->willReturn('span');

        $expected = [
            'attributes' => ['class' => 'btn btn-primary'],
            'content' => ['text'],
            'tag' => 'span',
        ];

        $this->assertEquals($expected, json_decode(json_encode($mock), JSON_OBJECT_AS_ARRAY));
    }

    public function testSetAttributes()
    {
        $mock = $this->getMockBuilder(Tag::class)
                ->setMethods(['setTagName'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setAttributes(['class' => 'test']);

        $this->assertInstanceOf(AttributeList::class, $mock->getAttributes());
    }

    public function testSetAttributesAttributeList()
    {
        $mock = $this->getMockBuilder(Tag::class)
                ->setMethods(['setTagName'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setAttributes((new AttributeList()));

        $this->assertInstanceOf(AttributeList::class, $mock->getAttributes());
    }

    public function testSetAttributesEmpty()
    {
        $mock = $this->getMockBuilder(Tag::class)
                ->setMethods(['setTagName'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setAttributes([]);

        $this->assertInstanceOf(AttributeList::class, $mock->getAttributes());
    }

    public function testSetAttributesWithException()
    {
        $mock = $this->getMockBuilder(Tag::class)
                ->setMethods(['setTagName'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(\InvalidArgumentException::class);

        $mock->setAttributes((new NodeList()));
    }

    public function testSetContent()
    {
        $mock = $this->getMockBuilder(Tag::class)
                ->setMethods(['setTagName'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setContent(['test text']);

        $this->assertInstanceOf(NodeList::class, $mock->getContent());
    }

    public function testSetContentEmpty()
    {
        $mock = $this->getMockBuilder(Tag::class)
                ->setMethods(['setTagName'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setContent([]);

        $this->assertInstanceOf(NodeList::class, $mock->getContent());
    }

    public function testSetTagName()
    {
        $mock = $this->getMockBuilder(Tag::class)
                ->setMethods(['isSelfClosing'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setTagName('test');

        $this->assertEquals('test', $mock->getTagName());
    }

    public function testSetTagNameWithException()
    {
        $mock = $this->getMockBuilder(Tag::class)
                ->setMethods(['isSelfClosing'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(\InvalidArgumentException::class);

        $mock->setTagName(123);
    }

    public function testSetContentSelfClosing()
    {
        $mock = $this->getMockBuilder(Tag::class)
                ->setMethods(['isSelfClosing'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('isSelfClosing')
                ->willReturn(true);

        $this->expectException(\BadMethodCallException::class);

        $mock->setContent(['test text']);
    }

    public function testToString()
    {
        $mock = $this->getMockBuilder(Tag::class)
                ->setMethods(['getAttributes', 'getContent', 'getTagName', 'isSelfClosing'])
                ->getMock();

        $mock->expects($this->once())
                ->method('getTagName')
                ->willReturn('div');
        $mock->expects($this->once())
                ->method('getAttributes')
                ->willReturn('class="btn btn-primary"');
        $mock->expects($this->once())
                ->method('isSelfClosing')
                ->willReturn(false);
        $mock->expects($this->once())
                ->method('getContent')
                ->willReturn('test text.');

        $expected = '<div class="btn btn-primary">test text.</div>';

        $this->assertEquals($expected, ((string) $mock));
    }

    public function testToStringSelfClosing()
    {
        $mock = $this->getMockBuilder(Tag::class)
                ->setMethods(['getAttributes', 'getTagName', 'isSelfClosing'])
                ->getMock();

        $mock->expects($this->once())
                ->method('getTagName')
                ->willReturn('br');
        $mock->expects($this->once())
                ->method('getAttributes')
                ->willReturn('style="display: none;"');
        $mock->expects($this->once())
                ->method('isSelfClosing')
                ->willReturn(true);

        $expected2 = '<br style="display: none;" />';

        $this->assertEquals($expected2, ((string) $mock));
    }

}
