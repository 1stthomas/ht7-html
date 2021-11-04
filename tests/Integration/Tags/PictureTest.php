<?php

namespace Ht7\Base\Tests\Integration\Tags;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Tags\Picture;

class PictureTest extends TestCase
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

        $pic = new Picture($img, $sets, ['class' => 'test-123']);

        $expected = '<picture class="test-123"><source media="(min-width:690px)"'
                . ' srcset="/test/image1.png" /><source media="(min-width:1100px)"'
                . ' srcset="/test/image2.png" /><img alt class="img img-responsive"'
                . ' src="/test/image.jpg" /></picture>';

        $this->assertEquals($expected, (string) $pic);
    }

}
