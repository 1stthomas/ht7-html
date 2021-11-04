<?php

namespace Ht7\Base\Tests\Integration\Tags;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Tag;
use \Ht7\Html\Text;
use \Ht7\Html\Lists\NodeList;

class NodeListTest extends TestCase
{

    public function testConstructor()
    {
        $items = [
            'text content 1',
            [
                'tag' => 'span',
                'attributes' => ['class' => 'container'],
                'content' => 'inner text',
            ],
            'text content 2',
        ];

        $expected = [
            [
                'class' => Text::class,
                'content' => 'text content 1',
            ],
            [
                'class' => Tag::class,
                'content' => [
                    'class' => Text::class,
                    'content' => 'inner text',
                ],
                'tag' => 'span',
            ],
            [
                'class' => Text::class,
                'content' => 'text content 2',
            ],
        ];

        $nL = new NodeList($items);

        foreach ($nL->getAll() as $key => $item) {
            $this->assertInstanceOf($expected[$key]['class'], $item);

            if ($expected[$key]['class'] === Text::class) {
                $this->assertEquals($expected[$key]['content'], $item->getContent());
            } elseif ($expected[$key]['class'] === Tag::class) {
                $this->assertEquals($expected[$key]['tag'], $item->getTagName());
                $this->contains(new Text($expected[$key]['content']['content']));
            }
        }
    }

}
