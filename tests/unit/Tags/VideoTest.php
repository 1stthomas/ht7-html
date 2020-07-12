<?php

namespace Ht7\Base\Tests;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Tags\Video;

class VideoTest extends TestCase
{

    public function testConstructor()
    {
        $className = Video::class;
        $video = ['height' => '250', 'width' => '400'];
        $srcSets = ['video/mp4' => 'movie.mp4', 'video/ogg' => 'movie.ogg'];
        $tracks = [
            ['src' => 'fgsubtitles_en.vtt', 'kind' => 'subtitles', 'srclang' => 'en', 'label' => 'English'],
            ['src' => 'fgsubtitles_no.vtt', 'kind' => 'subtitles', 'srclang' => 'no', 'label' => 'Norwegian']
        ];
        $text = ['Your browser does not support the video tag.'];

        $expectedSrcSets = [
            [
                'attributes' => ['src' => 'movie.mp4', 'type' => 'video/mp4'],
                'tag' => 'source'
            ],
            [
                'attributes' => ['src' => 'movie.ogg', 'type' => 'video/ogg'],
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
                ->with($this->equalTo($video));
        $mock->expects($this->once())
                ->method('setContent')
                ->with($this->equalTo($content));
        $mock->expects($this->once())
                ->method('setTagName')
                ->with($this->equalTo('video'));

        $reflectedClass = new \ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invokeArgs($mock, [$video, $srcSets, $tracks, $text]);
    }

}
