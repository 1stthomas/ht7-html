<?php

namespace Ht7\Base\Tests;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Tags\Audio;

/**
 * Test class for the SelfClosing class.
 *
 * @author      Thomas Pluess
 * @since       0.0.1
 * @version     0.0.1
 * @copyright (c) 2019, Thomas Pluess
 */
class AudioTest extends TestCase
{

    public function testConstructor()
    {
        $className = Audio::class;
        $audio = ['controls' => '', 'autoplay' => ''];
        $srcSets = ['audio/mpeg' => 'audio.mp3', 'audio/ogg' => 'audio.ogg'];
        $tracks = [
            ['src' => 'fgsubtitles_en.vtt', 'kind' => 'subtitles', 'srclang' => 'en', 'label' => 'English'],
            ['src' => 'fgsubtitles_no.vtt', 'kind' => 'subtitles', 'srclang' => 'no', 'label' => 'Norwegian']
        ];
        $text = ['Your browser does not support the audio tag.'];

        $expectedSrcSets = [
            [
                'attributes' => ['src' => 'audio.mp3', 'type' => 'audio/mpeg'],
                'tag' => 'source'
            ],
            [
                'attributes' => ['src' => 'audio.ogg', 'type' => 'audio/ogg'],
                'tag' => 'source'
            ]
        ];
        $expectedTracks = [
            [
                'attributes' => $tracks[0],
                'tag' => 'track'
            ],
            [
                'attributes' => $tracks[1],
                'tag' => 'track'
            ]
        ];
        $content = [$expectedSrcSets, $expectedTracks, [$text]];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setAttributes', 'setContent', 'setTagName', 'setUp'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setUp')
                ->with($this->equalTo($srcSets), $this->equalTo($tracks), $this->equalTo($text))
                ->willReturn($content);
        $mock->expects($this->once())
                ->method('setAttributes')
                ->with($this->equalTo($audio));
        $mock->expects($this->once())
                ->method('setContent')
                ->with($this->equalTo($content));
        $mock->expects($this->once())
                ->method('setTagName')
                ->with($this->equalTo('audio'));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invokeArgs($mock, [$audio, $srcSets, $tracks, $text]);
    }

}
