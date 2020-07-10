<?php

namespace Ht7\Base\Tests;

use \InvalidArgumentException;
use \stdClass;
use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Text;

/**
 * Test class for the SelfClosing class.
 *
 * @author      Thomas Pluess
 * @since       0.0.1
 * @version     0.0.1
 * @copyright (c) 2019, Thomas Pluess
 */
class TextTest extends TestCase
{

    public function testConstructor()
    {
        $str = 'testtest..';

        $text = new Text($str);

        $this->assertIsString($text->getContent());
        $this->assertEquals($str, $text->getContent());
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

    public function testWithException()
    {
        $this->expectException(InvalidArgumentException::class);

        new Text((new stdClass()));
    }

}
