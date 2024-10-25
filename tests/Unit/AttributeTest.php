<?php

namespace Ht7\Html\Tests\Unit;

use \InvalidArgumentException;
use \stdClass;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use Ht7\Html\Attribute;

class AttributeTest extends TestCase
{
    private string $className = Attribute::class;

    #[Test]
    #[TestDox('Get name.')]
    public function getName(): void
    {
        $expected = 'class';
        /** @var Attribute $sut */
        $sut = $this->getSut(['setName']);

        $reflectedClass = new \ReflectionClass($this->className);
        $property = $reflectedClass->getProperty('name');
        $property->setAccessible(true);
        $property->setValue($sut, $expected);

        $this->assertEquals($expected, $sut->getName());
    }

    #[Test]
    #[TestDox('Get value.')]
    public function getValue(): void
    {
        $expected = 'btn btn-primary';
        /** @var Attribute $sut */
        $sut = $this->getSut(['setName']);

        $reflectedClass = new \ReflectionClass($this->className);
        $property = $reflectedClass->getProperty('value');
        $property->setAccessible(true);
        $property->setValue($sut, $expected);

        $this->assertEquals($expected, $sut->getValue());
    }

    #[Test]
    #[TestDox('Json encode.')]
    public function jsonEncode(): void
    {
        $expected = '"btn btn-primary"';
        $sut = $this->getSut(['getValue']);

        $sut->expects($this->once())
                ->method('getValue')
                ->willReturn('btn btn-primary');

        $this->assertEquals($expected, json_encode($sut));
    }

    #[Test]
    #[TestDox('Set name with exception.')]
    public function setNameWithException(): void
    {
        /** @var Attribute $sut */
        $sut = $this->getSut(['setValue']);

        $this->expectException(\InvalidArgumentException::class);

        $sut->setName('');
    }

    #[Test]
    #[TestDox('Render the attribute.')]
    public function render(): void
    {
        $expected = 'class="btn btn-primary"';
        $sut = $this->getSut(['getName', 'getValue']);

        $sut->expects($this->once())
                ->method('getName')
                ->willReturn('class');
        $sut->expects($this->once())
                ->method('getValue')
                ->willReturn('btn btn-primary');

        $actual = (string) $sut;

        $this->assertEquals($expected, $actual);
    }

    #[Test]
    #[TestDox('Render the attribute with no value.')]
    public function renderNoValue(): void
    {
        $expected = 'required';
        $sut = $this->getSut(['getName', 'getValue']);

        $sut->expects($this->once())
                ->method('getName')
                ->willReturn('required');
        $sut->expects($this->once())
                ->method('getValue')
                ->willReturn('');

        $actual = (string) $sut;

        $this->assertEquals($expected, $actual);
    }

    private function getSut(array $methods): MockObject
    {
        return $this->getMockBuilder($this->className)
                ->onlyMethods($methods)
                ->disableOriginalConstructor()
                ->getMock();
    }

}
