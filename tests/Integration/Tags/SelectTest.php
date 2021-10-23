<?php

namespace Ht7\Base\Tests\Integration\Tags;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Tags\Select;

class SelectTest extends TestCase
{

    public function testRenderPlain()
    {
        $select = new Select(
                [['test 1', '1'], ['test 1', '2', 1], ['test 3', '3', 0, ['data-url' => 'test/file.png']]],
                ['name' => 'select-test', 'class' => 'form-control']
        );

        $expected = '<select class="form-control" name="select-test"><option'
                . ' value="1">test 1</option><option selected value="2">test'
                . ' 1</option><option data-url="test/file.png" value="3">test'
                . ' 3</option></select>';

        $this->assertEquals($expected, (string) $select);
    }

    public function testRenderWithOptgroup()
    {
        $select = new Select(
                [
            'Erstes Label' => [['test 1', '1'], ['test 1', '2', 1]],
            'Zweites Label' => [['test 3', '3', 0, ['data-url' => 'test/file.png']]]
                ],
                ['name' => 'select-test', 'class' => 'form-control']
        );

        $expected = '<select class="form-control" name="select-test"><optgroup'
                . ' label="Erstes Label"><option value="1">test 1</option><option'
                . ' selected value="2">test 1</option></optgroup><optgroup'
                . ' label="Zweites Label"><option data-url="test/file.png"'
                . ' value="3">test 3</option></optgroup></select>';

        $this->assertEquals($expected, (string) $select);
    }

}
