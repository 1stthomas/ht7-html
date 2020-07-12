<?php

namespace Ht7\Base\Tests;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Utilities\AbstractMedia;

class AbstractMediaTest extends TestCase
{

    public function testSetUp()
    {
        $className = AbstractMedia::class;
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

        $mock = $this->getMockBuilder($className)
                ->setMethods(['translateSrcSets', 'translateTracks'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('translateSrcSets')
                ->with($this->equalTo($srcSets))
                ->willReturn($expectedSrcSets);
        $mock->expects($this->once())
                ->method('translateTracks')
                ->with($this->equalTo($tracks))
                ->willReturn($expectedTracks);

        $reflectedClass = new \ReflectionClass($className);
        $method = $reflectedClass->getMethod('setUp');
        $method->setAccessible(true);

        $expected = array_merge($expectedSrcSets, $expectedTracks, [$text]);
        $actual = $method->invokeArgs($mock, [$srcSets, $tracks, $text]);

        $this->assertEquals($expected, $actual);
    }

    public function testSetUpOnlyText()
    {
        $className = AbstractMedia::class;
        $srcSets = [];
        $tracks = [];
        $text = ['Your browser does not support the audio tag.'];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['translateSrcSets', 'translateTracks'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('translateSrcSets')
                ->with($this->equalTo($srcSets))
                ->willReturn([]);
        $mock->expects($this->once())
                ->method('translateTracks')
                ->with($this->equalTo($tracks))
                ->willReturn([]);

        $reflectedClass = new \ReflectionClass($className);
        $method = $reflectedClass->getMethod('setUp');
        $method->setAccessible(true);

        $expected = [$text];
        $actual = $method->invokeArgs($mock, [$srcSets, $tracks, $text]);

        $this->assertEquals($expected, $actual);
    }

    public function testTranslateSrcSets()
    {
        $className = AbstractMedia::class;
        $sets = ['audio/mpeg' => 'audio.mp3', 'audio/ogg' => 'audio.ogg'];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setUp'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $method = $reflectedClass->getMethod('translateSrcSets');
        $method->setAccessible(true);

        $expected = [
            [
                'attributes' => ['src' => 'audio.mp3', 'type' => 'audio/mpeg'],
                'tag' => 'source'
            ],
            [
                'attributes' => ['src' => 'audio.ogg', 'type' => 'audio/ogg'],
                'tag' => 'source'
            ]
        ];
        $actual = $method->invokeArgs($mock, [$sets]);

        $this->assertEquals($expected, $actual);
    }

    public function testTranslateTracks()
    {
        $className = AbstractMedia::class;
        $tracks = [
            ['src' => 'fgsubtitles_en.vtt', 'kind' => 'subtitles', 'srclang' => 'en', 'label' => 'English'],
            ['src' => 'fgsubtitles_no.vtt', 'kind' => 'subtitles', 'srclang' => 'no', 'label' => 'Norwegian']
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setUp'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $method = $reflectedClass->getMethod('translateTracks');
        $method->setAccessible(true);

        $expected = [
            [
                'attributes' => $tracks[0],
                'tag' => 'track'
            ],
            [
                'attributes' => $tracks[1],
                'tag' => 'track'
            ]
        ];
        $actual = $method->invokeArgs($mock, [$tracks]);

        $this->assertEquals($expected, $actual);
    }

}
