<?php

namespace Ht7\Base\Tests\Functional;

use \BadMethodCallException;
use \InvalidArgumentException;
use \stdClass;
use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Node;
use \Ht7\Html\Tag;
use \Ht7\Html\Iterators\PreOrderIterator;
use \Ht7\Html\Lists\AttributeList;
use \Ht7\Html\Lists\NodeList;

/**
 * Test class for the SelfClosing class.
 *
 * @author      Thomas Pluess
 * @since       0.0.1
 * @version     0.0.1
 * @copyright (c) 2019, Thomas Pluess
 */
class TagTest extends TestCase
{

    private $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Tag('div', ['bla']);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

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
        $this->assertInstanceOf(AttributeList::class, $this->object->getAttributes());
    }

    public function testGetContent()
    {
        $this->assertInstanceOf(NodeList::class, $this->object->getContent());
    }

    public function testGetIteratorPreOrder()
    {
        $this->assertInstanceOf(PreOrderIterator::class, $this->object->getIteratorPreOrder());
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

        $tag = $this->getMockBuilder(Tag::class)
                ->setMethods(['getAttributes', 'getContent', 'getTagName'])
                ->getMock();

        $tag->expects($this->once())
                ->method('getAttributes')
                ->willReturn($alMock);
        $tag->expects($this->once())
                ->method('getContent')
                ->willReturn($nlMock);
        $tag->expects($this->once())
                ->method('getTagName')
                ->willReturn('span');

        $expected = [
            'attributes' => ['class' => 'btn btn-primary'],
            'content' => ['text'],
            'tag' => 'span',
        ];

        $this->assertEquals($expected, $tag->jsonSerialize());
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
        $tag = $this->getMockBuilder(Tag::class)
                ->setMethods(['getAttributes', 'getContent', 'getTagName', 'isSelfClosing'])
                ->getMock();

        $tag->expects($this->once())
                ->method('getTagName')
                ->willReturn('div');
        $tag->expects($this->once())
                ->method('getAttributes')
                ->willReturn('class="btn btn-primary"');
        $tag->expects($this->once())
                ->method('isSelfClosing')
                ->willReturn(false);
        $tag->expects($this->once())
                ->method('getContent')
                ->willReturn('test text.');

        $expected = '<div class="btn btn-primary">test text.</div>';

        $this->assertEquals($expected, ((string) $tag));
    }

    public function testToStringSelfClosing()
    {
        $tag2 = $this->getMockBuilder(Tag::class)
                ->setMethods(['getAttributes', 'getTagName', 'isSelfClosing'])
                ->getMock();

        $tag2->expects($this->once())
                ->method('getTagName')
                ->willReturn('br');
        $tag2->expects($this->once())
                ->method('getAttributes')
                ->willReturn('style="display: none;"');
        $tag2->expects($this->once())
                ->method('isSelfClosing')
                ->willReturn(true);

        $expected2 = '<br style="display: none;" />';

        $this->assertEquals($expected2, ((string) $tag2));
    }

}
