<?php

namespace Ht7\Html\Tests\Unit\Tags;

use \PHPUnit\Framework\TestCase;
use \Ht7\Base\Exceptions\InvalidDatatypeException;
use \Ht7\Html\Tag;
use \Ht7\Html\Lists\NodeList;
use \Ht7\Html\Tags\Select;

class SelectTest extends TestCase
{

    public function testConstructor()
    {
        $className = Select::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setAttributes', 'setContent', 'setTagName'])
                ->disableOriginalConstructor()
                ->getMock();

        $attr = ['class' => 'form-control'];
        $content = [['test 1', '1'], ['test 2', '2', 1], ['test 3', '3', 0, ['data-url' => 'test/file.png']]];

        $mock->expects($this->once())
                ->method('setAttributes')
                ->with($this->equalTo($attr));
        $mock->expects($this->once())
                ->method('setContent')
                ->with($this->equalTo($content));
        $mock->expects($this->once())
                ->method('setTagName')
                ->with($this->equalTo('select'));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invokeArgs($mock, [$content, $attr]);
    }

    public function testAdd()
    {
        $nL = $this->createMock(NodeList::class);

        $nL->expects($this->exactly(3))
                ->method('add')
                ->withConsecutive([$this->callback(function(Tag $subject) {
                                return $subject->getTagName() === 'option' && (string) $subject->getContent() === 'test 1' && $subject->getAttributes()->get('value')->getValue() === '1';
                            })],
                        [$this->callback(function(Tag $subject) {
                                        return $subject->getTagName() === 'option' && (string) $subject->getContent() === 'test 2' && $subject->getAttributes()->get('value')->getValue() === '2' && $subject->getAttributes()->has('selected');
                                    })],
                        [$this->callback(function(Tag $subject) {
                                        return $subject->getTagName() === 'option' && (string) $subject->getContent() === 'test 3' && $subject->getAttributes()->get('value')->getValue() === '3' && $subject->getAttributes()->get('data-url')->getValue() === 'test/file.png';
                                    })]);

        $className = Select::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setTagName'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('content');
        $property->setAccessible(true);
        $property->setValue($mock, $nL);

        $content1 = ['test 1', '1'];
        $content2 = ['test 2', '2', 1];
        $content3 = ['test 3', '3', 0, ['data-url' => 'test/file.png']];

        $mock->add($content1);
        $mock->add($content2);
        $mock->add($content3);
    }

//    public function testAddWithContainer()
//    {
//        $nL = $this->createMock(NodeList::class);
//
//        $nL->expects($this->never())
//                ->method('add');
//
//        $className = Select::class;
//
//        $mock = $this->getMockBuilder($className)
//                ->setMethods(['setTagName'])
//                ->disableOriginalConstructor()
//                ->getMock();
//
//        $reflectedClass = new \ReflectionClass($className);
//        $property = $reflectedClass->getProperty('content');
//        $property->setAccessible(true);
//        $property->setValue($mock, $nL);
//
//        $optGrp = $this->createMock(Tag::class);
//
//        $optGrp->expects($this->exactly(3))
//                ->method('add')
//                ->withConsecutive([$this->callback(function(Tag $subject) {
//                                return $subject->getTagName() === 'option' && (string) $subject->getContent() === 'test 1' && $subject->getAttributes()->get('value')->getValue() === '1';
//                            })],
//                        [$this->callback(function(Tag $subject) {
//                                        return $subject->getTagName() === 'option' && (string) $subject->getContent() === 'test 2' && $subject->getAttributes()->get('value')->getValue() === '2' && $subject->getAttributes()->has('selected');
//                                    })],
//                        [$this->callback(function(Tag $subject) {
//                                        return $subject->getTagName() === 'option' && (string) $subject->getContent() === 'test 3' && $subject->getAttributes()->get('value')->getValue() === '3' && $subject->getAttributes()->get('data-url')->getValue() === 'test/file.png';
//                                    })]);
//
//        $content1 = ['test 1', '1'];
//        $content2 = ['test 2', '2', 1];
//        $content3 = ['test 3', '3', 0, ['data-url' => 'test/file.png']];
//
//        $mock->add($content1, $optGrp);
//        $mock->add($content2, $optGrp);
//        $mock->add($content3, $optGrp);
//    }

    public function testSetContent()
    {
        $className = Select::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['add'])
                ->disableOriginalConstructor()
                ->getMock();

        $content = [['test 1', '1'], ['test 2', '2', 1], ['test 3', '3', 0, ['data-url' => 'test/file.png']]];

        $mock->expects($this->exactly(3))
                ->method('add')
                ->withConsecutive(
                        [$this->equalTo($content[0])],
                        [$this->equalTo($content[1])],
                        [$this->equalTo($content[2])]
        );

        $mock->setContent($content);
    }

    public function testSetContentWithException()
    {
        $className = Select::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setTagName'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(InvalidDatatypeException::class);

        $mock->setContent('should fail');
    }

    public function testSetContentWithOptGroup()
    {
        $className = Select::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['add'])
                ->disableOriginalConstructor()
                ->getMock();

        $content = [
            'grp 1' => [
                ['test 1', '1'], ['test 2', '2', 1]
            ],
            'grp 2' => [['test 3', '3', 0, ['data-url' => 'test/file.png']]]
        ];

        $mock->expects($this->exactly(5))
                ->method('add')
                ->withConsecutive(
                        [$this->equalTo($content['grp 1'][0]), $this->callback(function (Tag $subject) {
                                        return $subject->getTagName() === 'optgroup';
                                    })],
                        [$this->equalTo($content['grp 1'][1]), $this->callback(function (Tag $subject) {
                                        return $subject->getTagName() === 'optgroup';
                                    })],
                        [$this->callback(function (Tag $subject) {
                                        return $subject->getTagName() === 'optgroup';
                                    }), $this->isNull()],
                        [$this->equalTo($content['grp 2'][0]), $this->callback(function (Tag $subject) {
                                        return $subject->getTagName() === 'optgroup';
                                    })],
                        [$this->callback(function (Tag $subject) {
                                        return $subject->getTagName() === 'optgroup';
                                    }), $this->isNull()]
        );

        $mock->setContent($content);
    }

}
