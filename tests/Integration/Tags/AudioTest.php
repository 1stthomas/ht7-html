<?php

namespace Ht7\Base\Tests\Integration\Tags;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Tags\Audio;

class AudioTest extends TestCase
{

    public function testRender()
    {
        $audioAttr = ['controls' => ''];
        $sets = ['audio/mpeg' => 'audio.mp3', 'audio/ogg' => 'audio.ogg'];
        $tracks = [
            ['src' => 'fgsubtitles_en.vtt', 'kind' => 'subtitles', 'srclang' => 'en', 'label' => 'English'],
            ['src' => 'fgsubtitles_no.vtt', 'kind' => 'subtitles', 'srclang' => 'no', 'label' => 'Norwegian']
        ];

        $audio = new Audio($audioAttr, $sets, $tracks, 'Your browser does not support the audio tag.');

        $expected = '<audio controls><source src="audio.mp3" type="audio/mpeg"'
                . ' /><source src="audio.ogg" type="audio/ogg" /><track'
                . ' kind="subtitles" label="English" src="fgsubtitles_en.vtt"'
                . ' srclang="en" /><track kind="subtitles" label="Norwegian"'
                . ' src="fgsubtitles_no.vtt" srclang="no" />Your browser does'
                . ' not support the audio tag.</audio>';

        $this->assertEquals($expected, (string) $audio);
    }

}
