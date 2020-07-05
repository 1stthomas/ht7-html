<?php

namespace Ht7\Base\Tests;

use \InvalidArgumentException;
use \stdClass;
use \PHPUnit\Framework\TestCase;
use \Ht7\Base\Exceptions\InvalidDatatypeException;
use \Ht7\Html\Tag;
use \Ht7\Html\Text;
use \Ht7\Html\Lists\NodeList;

/**
 * Test class for the NodeList class.
 *
 * @author      Thomas Pluess
 * @since       0.0.1
 * @version     0.0.1
 * @copyright (c) 2019, Thomas Pluess
 */
class NodeListTest extends TestCase
{

    private $object;

    protected function setUp()
    {
        $this->object = new NodeList();
    }

    protected function tearDown()
    {

    }

    public function testAdd()
    {
        $this->object->add('test string');
        $this->object->add(new Tag());
        $this->object->add(new Text('simple text text.'));
        $this->object->add([
            'attributes' => ['class' => 'btn'],
            'content' => ['string'],
            'tag' => 'div'
        ]);

        $this->assertCount(4, $this->object);

        $this->expectException(InvalidDatatypeException::class);

        $this->object->add(new \stdClass());
    }

    public function testJsonEncode()
    {
        $data = [
            (new Text('test 1')),
            (new Text('test 2')),
            (new Tag('div', ['test 3']))
        ];
        $nL = new NodeList($data);

        $expected = '["test 1","test 2",';
        $expected .= '{"attributes":[],"content":["test 3"],"tag":"div"}]';

        $actual = json_encode($nL);

        $this->assertEquals($expected, $actual);
    }

    public function testSetDivider()
    {
        $nL = new NodeList(['t1', 't2']);
        $nL->setDivider(';');

        $expected = 't1;t2';
        $actual = '' . $nL;

        $this->assertEquals($expected, $actual);

        $this->expectException(InvalidDatatypeException::class);

        $nL->setDivider([]);
    }

}
