<?php

namespace Ht7\Base\Tests;

use \InvalidArgumentException;
use \stdClass;
use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Attribute;

/**
 * Test class for the SelfClosing class.
 *
 * @author      Thomas Pluess
 * @since       0.0.1
 * @version     0.0.1
 * @copyright (c) 2019, Thomas Pluess
 */
class AttributeTest extends TestCase
{

    private $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
//        $this->object = new Tag();
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
        $attr = new Attribute('class', 'btn btn-alert');

        $this->assertInstanceOf(Attribute::class, $attr);

        return $attr;
    }

    /**
     * @depends testConstructor
     */
    public function testRender(Attribute $attr)
    {
        $actual = (string) $attr;
        $expected = 'class="btn btn-alert"';

        $this->assertEquals($expected, $actual);

        return $attr;
    }

    /**
     * @depends testRender
     */
    public function testSetName(Attribute $attr)
    {
        $attr->setName('test');

        $this->assertEquals('test', $attr->getName());

        return $attr;
    }

    /**
     * @depends testRender
     */
    public function testSetNameEmpty(Attribute $attr)
    {
        $this->expectException(InvalidArgumentException::class);

        $attr->setName('');

        return $attr;
    }

    /**
     * @depends testRender
     */
    public function testSetNameNotString(Attribute $attr)
    {
        $this->expectException(InvalidArgumentException::class);

        $attr->setName(100.33);

        return $attr;
    }

    /**
     * @depends testSetName
     */
    public function testSetValueWithException(Attribute $attr)
    {
        $this->expectException(InvalidArgumentException::class);

        $attr->setValue((new stdClass()));

        return $attr;
    }

}
