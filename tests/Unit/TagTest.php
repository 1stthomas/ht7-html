<?php

namespace Ht7\Html\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ht7\Html\Tag;
use Ht7\Html\Text;
use Ht7\Html\Iterators\PreOrderIterator;
use Ht7\Html\Lists\AttributeList;
use Ht7\Html\Lists\NodeList;

class TagTest extends TestCase
{
    private string $className = Tag::class;

    #[Test]
    #[TestDox('Tag initialisation.')]
    public function tagConstructor(): void
    {
        // see: http://miljar.github.io/blog/2013/12/20/phpunit-testing-the-constructor/
        $tagName = 'span';
        $content = ['test text'];
        $attributes = ['class' => 'btn btn-primary'];

        $sut = $this->getMockTag(['setTagName', 'setContent', 'setAttributes']);
        $sut->expects($this->never())
                ->method('setTagName');
        $sut->expects($this->once())
                ->method('setContent')
                ->with($this->equalTo($content))
                ->willReturnSelf();
        $sut->expects($this->once())
                ->method('setAttributes')
                ->with($this->equalTo($attributes))
                ->willReturnSelf();

        $reflectedClass = new \ReflectionClass($this->className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($sut, $tagName, $content, $attributes);
    }

    #[Test]
    #[TestDox('Get attributes.')]
    public function getAttributes(): void
    {
        $tag1 = new Tag('div', ['bla']);

        $this->assertInstanceOf(AttributeList::class, $tag1->getAttributes());

        $tag2 = new Tag('div', ['bla'], ['class' => 'btn']);

        $this->assertInstanceOf(AttributeList::class, $tag2->getAttributes());
    }

    #[Test]
    #[TestDox('Get content.')]
    public function getContent(): void
    {
        $tag1 = new Tag('div');

        $this->assertInstanceOf(NodeList::class, $tag1->getContent());

        $tag2 = new Tag('div', ['bla']);

        $this->assertInstanceOf(NodeList::class, $tag2->getContent());
    }

    #[Test]
    #[TestDox('Get the default iterator.')]
    public function getIterator(): void
    {
        $tag1 = new Tag('div');

        $this->assertInstanceOf(PreOrderIterator::class, $tag1->getIterator());

        $tag2 = new Tag('div', ['bla']);

        $this->assertInstanceOf(PreOrderIterator::class, $tag2->getIterator());
    }

    #[Test]
    #[TestDox('Get the perorder iterator.')]
    public function getIteratorPreOrder(): void
    {
        $tag1 = new Tag('div');

        $this->assertInstanceOf(PreOrderIterator::class, $tag1->getIteratorPreOrder());

        $tag2 = new Tag('div', ['bla']);

        $this->assertInstanceOf(PreOrderIterator::class, $tag2->getIteratorPreOrder());
    }

    #[Test]
    #[TestDox('Json serialize.')]
    public function jsonSerialize(): void
    {
        $nlMock = $this->createMock(NodeList::class);

        $nlMock->expects($this->once())
                ->method('jsonSerialize')
                ->willReturn(['text']);

        $alMock = $this->createMock(AttributeList::class);

        $alMock->expects($this->once())
                ->method('jsonSerialize')
                ->willReturn(['class' => 'btn btn-primary']);

        $sut = $this->getMockTag(['getAttributes', 'getContent', 'getTagName']);
        $sut->expects($this->once())
                ->method('getAttributes')
                ->willReturn($alMock);
        $sut->expects($this->once())
                ->method('getContent')
                ->willReturn($nlMock);
        $sut->expects($this->once())
                ->method('getTagName')
                ->willReturn('span');

        $expected = [
            'attributes' => ['class' => 'btn btn-primary'],
            'content' => ['text'],
            'tag' => 'span',
        ];

        $this->assertEquals($expected, json_decode(json_encode($sut), JSON_OBJECT_AS_ARRAY));
    }

    #[Test]
    #[TestDox('Set attributes.')]
    public function setAttributes(): void
    {
        $attributes = ['class' => 'btn btn-primary'];
        $sut = $this->getMockTag([]);

        /** @var Tag $sut */
        $sut->setAttributes($attributes);

        $return = $sut->getAttributes();
        $this->assertInstanceOf(AttributeList::class, $return);
        $reflectedClassAttrList = new \ReflectionClass(AttributeList::class);
        $itemsProperty = $reflectedClassAttrList->getProperty('items');
        $itemsProperty->setAccessible(true);
        $items = $itemsProperty->getValue($return);
        $this->assertCount(1, $items);
    }

    #[Test]
    #[TestDox('Set attributes with an attribute list.')]
    public function setAttributesAttributeList(): void
    {
        $attrList = new AttributeList();
        $sut = $this->getMockTag([]);

        /** @var Tag $sut */
        $sut->setAttributes($attrList);

        $this->assertSame($attrList, $sut->getAttributes());
    }

    #[Test]
    #[TestDox('Set attributes with an empty array.')]
    public function setAttributesEmpty(): void
    {
        $sut = $this->getMockTag([]);

        /** @var Tag $sut */
        $sut->setAttributes([]);

        $attrList = $sut->getAttributes();
        $this->assertInstanceOf(AttributeList::class, $attrList);
        $this->assertEmpty($attrList);
    }

    #[Test]
    #[TestDox('Set content.')]
    public function setContent(): void
    {
        $content = ['test text'];
        $sut = $this->getMockTag([]);

        $reflectedClass = new \ReflectionClass($this->className);
        $tagName = $reflectedClass->getProperty('tagName');
        $tagName->setAccessible(true);
        $tagName->setValue($sut, 'div');

        /** @var Tag $sut */
        $sut->setContent($content);

        $return = $sut->getContent();
        $this->assertInstanceOf(NodeList::class, $return);
        $reflectedClassNodeList = new \ReflectionClass(NodeList::class);
        $itemsProperty = $reflectedClassNodeList->getProperty('items');
        $itemsProperty->setAccessible(true);
        $items = $itemsProperty->getValue($return);
        $this->assertCount(1, $items);
        $this->assertInstanceOf(Text::class, $items[0]);
        $reflectedClassText = new \ReflectionClass(Text::class);
        $contentProperty = $reflectedClassText->getProperty('content');
        $contentProperty->setAccessible(true);
        $contentFromProperty = $contentProperty->getValue($items[0]);
        $this->assertSame($content[0], $contentFromProperty);
    }

    #[Test]
    #[TestDox('Set content with an empty array.')]
    public function setContentEmpty(): void
    {
        $sut = $this->getMockTag([]);

        $reflectedClass = new \ReflectionClass($this->className);
        $tagName = $reflectedClass->getProperty('tagName');
        $tagName->setAccessible(true);
        $tagName->setValue($sut, 'div');

        /** @var Tag $sut */
        $sut->setContent([]);

        $content = $sut->getContent();
        $this->assertInstanceOf(NodeList::class, $content);
        $reflectedClass = new \ReflectionClass(NodeList::class);
        $items = $reflectedClass->getProperty('items');
        $items->setAccessible(true);
        $this->assertEmpty($items->getValue($content));
    }

    #[Test]
    #[TestDox('Set tag name.')]
    public function setTagName(): void
    {
        $tagName = 'test';
        $sut = $this->getMockTag([]);

        /** @var Tag $sut */
        $return = $sut->setTagName($tagName);

        $this->assertEquals($tagName, $sut->getTagName());
        $this->assertSame($sut, $return);
    }

    #[Test]
    #[TestDox('Set content self closing with an exception.')]
    public function setContentSelfClosing(): void
    {
        $sut = $this->getMockTag(['isSelfClosing']);
        $sut->expects($this->once())
                ->method('isSelfClosing')
                ->willReturn(true);

        $reflectedClass = new \ReflectionClass($this->className);
        $tagName = $reflectedClass->getProperty('tagName');
        $tagName->setAccessible(true);
        $tagName->setValue($sut, 'br');

        $this->expectException(\BadMethodCallException::class);

        /** @var Tag $sut */
        $sut->setContent(['test text']);
    }

    #[Test]
    #[TestDox('Type conversion to string.')]
    public function render(): void
    {
        $tagName = 'div';
        $attr = 'class="btn btn-primary"';
        $content = 'test text.';
        $attrList = $this->getMockAttributeList($attr);
        $nodeList = $this->getMockBuilder(NodeList::class)
                ->onlyMethods(['__toString'])
                ->getMock();
        $nodeList->expects($this->once())
                ->method('__toString')
                ->willReturn($content);

        $sut = $this->getMockTag(['getAttributes', 'getContent', 'getTagName', 'isSelfClosing']);
        $sut->expects($this->once())
                ->method('getTagName')
                ->willReturn($tagName);
        $sut->expects($this->once())
                ->method('getAttributes')
                ->willReturn($attrList);
        $sut->expects($this->once())
                ->method('isSelfClosing')
                ->willReturn(false);
        $sut->expects($this->once())
                ->method('getContent')
                ->willReturn($nodeList);

        $expected = "<{$tagName} {$attr}>{$content}</{$tagName}>";

        $this->assertEquals($expected, ((string) $sut));
    }

    #[Test]
    #[TestDox('Type conversion to string as self closing.')]
    public function toStringSelfClosing(): void
    {
        $tagName = 'br';
        $attr = 'style="display: none;"';
        $attrList = $this->getMockAttributeList($attr);

        $sut = $this->getMockTag(['getAttributes', 'getContent', 'getTagName', 'isSelfClosing']);
        $sut->expects($this->once())
                ->method('getTagName')
                ->willReturn($tagName);
        $sut->expects($this->once())
                ->method('getAttributes')
                ->willReturn($attrList);
        $sut->expects($this->once())
                ->method('isSelfClosing')
                ->willReturn(true);
        $sut->expects($this->never())
                ->method('getContent');

        $expected = "<{$tagName} {$attr} />";

        $this->assertEquals($expected, ((string) $sut));
    }

    final private function getMockAttributeList(string $attr = ''): MockObject
    {
        $attrList = $this->getMockBuilder(AttributeList::class)
                ->onlyMethods(['__toString'])
                ->getMock();
        $attrList->expects($this->once())
                ->method('__toString')
                ->willReturn($attr);
        
        return $attrList;
    }

    final private function getMockTag(array $methods = []): MockObject
    {
        return $this->getMockBuilder(Tag::class)
                ->onlyMethods($methods)
                ->disableOriginalConstructor()
                ->getMock();
    }

}
