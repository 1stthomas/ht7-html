<?php

namespace Ht7\Base\Tests;

use \InvalidArgumentException;
use \stdClass;
use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Attribute;
use \Ht7\Html\Lists\AttributeList;

/**
 * Test class for the SelfClosing class.
 *
 * @author      Thomas Pluess
 * @since       0.0.1
 * @version     0.0.1
 * @copyright (c) 2019, Thomas Pluess
 */
class AttributeListTest extends TestCase
{

    private $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new AttributeList();
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
        $data = [
            (new Attribute('class', 'btn')),
            (new Attribute('id', 'sidebar-btn'))
        ];

        $aL = new AttributeList($data);

        $this->assertInstanceOf(AttributeList::class, $aL);
        $this->assertCount(2, $aL);
        $this->assertCount(2, $aL->getAll());
    }

    public function testAdd()
    {
        $this->assertCount(0, $this->object);

        $this->object->add((new Attribute('href', 'http://abc.com')));

        $this->assertCount(1, $this->object);

        $this->object->add((new Attribute('class', 'left-link')));

        $this->assertCount(2, $this->object);

        return $this->object;
    }

    public function testHasByValue()
    {
        $this->object->add((new Attribute('class', 'btn btn-default')));
        $this->object->add((new Attribute('id', 'test-123')));

        $this->assertTrue($this->object->hasByValue('test-123'));
        $this->assertFalse($this->object->hasByValue('test-12'));
    }

    /**
     * @depends testAdd
     */
    public function testRender(AttributeList $aL)
    {
        $actual = (string) $aL;
        $expected = 'class="left-link" href="http://abc.com"';

        $this->assertEquals($expected, $actual);
    }

    public function testWithExceptionAdd()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->object->add('Should fail');
    }

}
