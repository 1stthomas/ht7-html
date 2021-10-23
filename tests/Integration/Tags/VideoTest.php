<?php

namespace Ht7\Base\Tests\Integration\Tags;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Tags\Video;

class VideoTest extends TestCase
{

    public function testRender()
    {
        $videoAttr = ['height' => '250', 'width' => '400'];
        $sets2 = ['video/mp4' => 'movie.mp4', 'video/ogg' => 'movie.ogg'];
        $tracks = [
            ['src' => 'fgsubtitles_en.vtt', 'kind' => 'subtitles', 'srclang' => 'en', 'label' => 'English'],
            ['src' => 'fgsubtitles_no.vtt', 'kind' => 'subtitles', 'srclang' => 'no', 'label' => 'Norwegian']
        ];

        $video = new Video($videoAttr, $sets2, $tracks, 'Your browser does not support the video tag.');

        $expected = '<video height="250" width="400"><source src="movie.mp4"'
                . ' type="video/mp4" /><source src="movie.ogg" type="video/ogg"'
                . ' /><track kind="subtitles" label="English" src="fgsubtitles_en.vtt"'
                . ' srclang="en" /><track kind="subtitles" label="Norwegian"'
                . ' src="fgsubtitles_no.vtt" srclang="no" />Your browser does'
                . ' not support the video tag.</video>';

        $this->assertEquals($expected, (string) $video);
    }

}
