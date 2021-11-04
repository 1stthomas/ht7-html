<?php

namespace Ht7\Base\Tests\Integration\Tags;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Tags\Figure;

class FigureTest extends TestCase
{

    public function testRender()
    {
        $img = [
            'src' => '/test/image.jpg',
            'alt' => '',
            'class' => 'img img-responsive'
        ];
        $sets = [
            690 => '/test/image1.png',
            1100 => '/test/image2.png'
        ];

        $fig = new Figure($img, ['Nice new image!'], $sets, ['class' => 'test-123']);

        $expected = '<figure class="test-123"><picture class="test-123"><source'
                . ' media="(min-width:690px)" srcset="/test/image1.png" /><source'
                . ' media="(min-width:1100px)" srcset="/test/image2.png" /><img'
                . ' alt class="img img-responsive" src="/test/image.jpg"'
                . ' /></picture><figcaption>Nice new image!</figcaption></figure>';

        $this->assertEquals($expected, (string) $fig);
    }

}
