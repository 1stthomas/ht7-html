<?php

namespace Ht7\Base\Tests\Integration;

use \InvalidArgumentException;
use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Models\SelfClosing;

class SelfClosingTest extends TestCase
{

    public function testAdd()
    {
        SelfClosing::add('test1');

        $this->assertTrue(SelfClosing::is('test1'));
    }

    public function testExpand()
    {
        SelfClosing::expand(['test01', 'test11', 'test22', 'aa', 'meta']);

        $this->assertTrue(SelfClosing::is('test01'));
        $this->assertTrue(SelfClosing::is('test22'));
        $this->assertTrue(SelfClosing::is('meta'));
        $this->assertTrue(SelfClosing::is('aa'));
        $this->assertFalse(SelfClosing::is('test'));

        $this->assertEquals('aa', SelfClosing::getAll()[0]);

        // Test for duplicates.
        $arr = array_keys(array_flip(SelfClosing::getAll()));

        $this->assertEquals(count(SelfClosing::getAll()), count($arr));
    }

    public function testGetAll()
    {
        $all = SelfClosing::getAll();

        $this->assertIsArray($all);
        $this->assertNotEmpty($all);
        $this->assertContains('img', $all);
    }

    /**
     *
     */
    public function testIs()
    {
        $this->assertTrue(SelfClosing::is('meta'));
        $this->assertFalse(SelfClosing::is('a'));
        $this->assertFalse(SelfClosing::is('no_entry'));
    }

}
