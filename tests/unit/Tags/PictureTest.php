<?php

namespace Ht7\Html\Tests\Unit\Tags;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Tags\Picture;

class PictureTest extends TestCase
{

    public function testConstructor()
    {
        $className = Picture::class;
        $img = ['src' => '/test/image.jpg', 'alt' => ''];
        $srcSets = [690 => '/test/image1.jpg', 1100 => '/test/image2.jpg'];
        $attr = ['class' => 'hyper-view'];

        $expectedSrcSet = [
            ['attributes' => ['media' => '(min-width:690px)', 'srcset' => '/test/image1.jpg'], 'tag' => 'source'],
            ['attributes' => ['media' => '(min-width:1100px)', 'srcset' => '/test/image2.jpg'], 'tag' => 'source']
        ];
        $expectedImg = ['attributes' => $img, 'content' => [], 'tag' => 'img'];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['translateSrcSets', 'translateImg', 'setTagName', 'setAttributes', 'setContent'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('translateSrcSets')
                ->with($this->equalTo($srcSets))
                ->willReturn($expectedSrcSet);
        $mock->expects($this->once())
                ->method('translateImg')
                ->with($this->equalTo($img))
                ->willReturn($expectedImg);

        $content = $expectedSrcSet;
        $content[] = $expectedImg;

        $mock->expects($this->once())
                ->method('setAttributes')
                ->with($this->equalTo($attr));
        $mock->expects($this->once())
                ->method('setContent')
                ->with($this->equalTo($content));
        $mock->expects($this->once())
                ->method('setTagName')
                ->with($this->equalTo('picture'));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invokeArgs($mock, [$img, $srcSets, $attr]);
    }

    public function testTranslateImg()
    {
        $className = Picture::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['translateSrcSets'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $method = $reflectedClass->getMethod('translateImg');
        $method->setAccessible(true);

        $attr = ['src' => '/test/image.jpg', 'alt' => ''];

        $expected = [
            'attributes' => $attr,
            'content' => [],
            'tag' => 'img'
        ];
        $actual = $method->invokeArgs($mock, [$attr]);

        $this->assertEquals($expected, $actual);
    }

    public function testTranslateSrcSet()
    {
        $className = Picture::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['translateImg'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $method = $reflectedClass->getMethod('translateSrcSets');
        $method->setAccessible(true);

        $srcSets = [690 => '/test/image1.jpg', 1100 => '/test/image2.jpg'];

        $expected = [
            ['attributes' => ['media' => '(min-width:690px)', 'srcset' => '/test/image1.jpg'], 'tag' => 'source'],
            ['attributes' => ['media' => '(min-width:1100px)', 'srcset' => '/test/image2.jpg'], 'tag' => 'source']
        ];
        $actual = $method->invokeArgs($mock, [$srcSets]);

        $this->assertEquals($expected, $actual);
    }

}
