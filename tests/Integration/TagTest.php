<?php

namespace Ht7\Base\Tests\Integration;

use \BadMethodCallException;
use \InvalidArgumentException;
use \stdClass;
use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Node;
use \Ht7\Html\Tag;
use \Ht7\Html\Lists\AttributeList;
use \Ht7\Html\Lists\NodeList;

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

    public function testEmptyTag()
    {
        $tag = new Tag();

        $expected = '<div></div>';
        $actual = '' . $tag;

        $this->assertEquals($expected, $actual);
    }

//    public function testJsonSerialize()
//    {
//        $attr = [
//            'class' => 'btn btn-primary',
//            'required'
//        ];
//        $content = ['testtest..'];
//
//        $tag = new Tag('div', $content, $attr);
//
//        $expected = [
//            'attributes' => ['class' => 'btn btn-primary', 'required' => ''],
//            'content' => ['testtest..'],
//            'tag' => 'div'
//        ];
//
//        $actual = $tag->jsonSerialize();
//
//        $this->assertEquals($expected, $actual);
//
//        $expected2 = '{"attributes":{"class":"btn btn-primary","required":""},';
//        $expected2 .= '"content":["testtest.."],';
//        $expected2 .= '"tag":"div"}';
//
//        $actual2 = json_encode($tag);
//
//        $this->assertEquals($expected2, $actual2);
//    }

    public function testRender()
    {
        $attr = [
            'class' => 'btn btn-primary',
            'required'
        ];
        $content = ['testtest..'];

        $tag = new Tag('div', $content, $attr);

        $expected = '<div class="btn btn-primary" required>testtest..</div>';

        $actual = (string) $tag;

        $this->assertEquals($expected, $actual);
    }

    public function testSelfClosingTag()
    {
        $tag = new Tag('br');

        $this->assertTrue($tag->isSelfClosing());

        $this->assertEquals('<br />', ((string) $tag));

        $this->expectException(BadMethodCallException::class);

        $tag->setContent(['should fail']);
    }

    public function testSetAttributes()
    {
        $tag = new Tag('br', [], ['class' => 'foo']);

        $expected = '<br class="foo" />';
        $actual = (string) $tag;

        $this->assertEquals($expected, $actual);

        $data = [
            'class' => 'bar foo-bar',
            'id' => 'foo-bar-123'
        ];
        $attributes = new AttributeList($data);
        $tag->setAttributes($attributes);

        $expected = '<br class="bar foo-bar" id="foo-bar-123" />';
        $actual = (string) $tag;

        $this->assertEquals($expected, $actual);
    }

}
