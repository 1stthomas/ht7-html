<?php

namespace Ht7\Html\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use Ht7\Html\Text;

class TextTest extends TestCase
{
    #[Test]
    #[TestDox('Tag initialisation.')]
    public function textConstructor(): void
    {
        $className = Text::class;
        $content = 'test text';

        $mock = $this->getMockBuilder($className)
                ->onlyMethods(['setContent'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setContent')
                ->with($this->equalTo($content));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $content);
    }

    #[Test]
    #[TestDox('Get content.')]
    public function getContent(): void
    {
        $className = Text::class;
        $content = 'test text';

        /** @var Text $sut */
        $sut = $this->getMockText([]);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('content');
        $property->setAccessible(true);

        $property->setValue($sut, $content);

        $this->assertEquals($content, $sut->getContent());
    }

    #[Test]
    #[TestDox('Json serialize.')]
    public function jsonSerialize(): void
    {
        $className = Text::class;
        $content = 'test text';

        $sut = $this->getMockText([]);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('content');
        $property->setAccessible(true);

        $property->setValue($sut, $content);

        $expected = '"' . $content . '"';
        $actual = json_encode($sut);

        $this->assertEquals($expected, $actual);
    }

    #[Test]
    #[TestDox('Set content.')]
    public function setContent(): void
    {
        $className = Text::class;
        $content = 'test text';

        /** @var Text $sut */
        $sut = $this->getMockText([]);

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('content');
        $property->setAccessible(true);

        $sut->setContent($content);
        $this->assertEquals($content, $property->getValue($sut));

        $content2 = 123;
        $sut->setContent($content2);
        $this->assertEquals($content2, $property->getValue($sut));

        $content3 = 123.001;
        $sut->setContent($content3);
        $this->assertEquals($content3, $property->getValue($sut));
    }

    #[Test]
    #[TestDox('Set content with an array and trigger exception.')]
    public function setContentWithException(): void
    {
        /** @var Text $sut */
        $sut = $this->getMockText([]);
        $this->expectException(\InvalidArgumentException::class);

        $sut->setContent([]);
    }

    #[Test]
    #[TestDox('Trigger __toString method.')]
    public function render(): void
    {
        $expected = 'test text.';

        $sut = $this->getMockText(['getContent']);
        $sut->expects($this->once())
                ->method('getContent')
                ->willReturn($expected);

        $this->assertEquals($expected, (string) $sut);
    }

    private function getMockText(array $methods): MockObject
    {
        return $this->getMockBuilder(Text::class)
                ->onlyMethods($methods)
                ->disableOriginalConstructor()
                ->getMock();
    }

}
