<?php

namespace Ht7\Base\Tests\Integration;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Callback;
use \Ht7\Html\Node;
use \Ht7\Html\Tag;

class CallbackTest extends TestCase
{

    public function testWithCallable()
    {
        $callback = [
            'type' => 'callback',
            'callable' => function() {
                return 'string from callable.';
            },
        ];
        $content = [
            [
                'tag' => 'div',
                'content' => ['test22222 {{', $callback, '}}']
            ]
        ];

        $tag = new Tag('div', $content);

        $this->assertTrue(true);
        $this->assertStringContainsString('{{string from callable.}}', (string) $tag);
    }

    public function testWithInstance()
    {
        $callback = [
            'type' => 'callback',
            'method' => 'getTestString01',
            'instance' => $this,
        ];
        $content = [
            [
                'tag' => 'div',
                'content' => ['test22222 {{', $callback, '}}']
            ]
        ];

        $tag = new Tag('div', $content);

        $this->assertTrue(true);
        $this->assertStringContainsString('{{test 123}}', (string) $tag);
    }

    public function testWithMethod()
    {
        if (file_exists('./assets/functions/callbacks.php')) {
            include_once './assets/functions/callbacks.php';
        } elseif (file_exists('./tests/assets/functions/callbacks.php')) {
            include_once './tests/assets/functions/callbacks.php';
        } else {
            throw new \BadMethodCallException('Missing callback functions file.');
        }

        $callback = [
            'type' => 'callback',
            'method' => 'callbackFunction01',
        ];
        $content = [
            [
                'tag' => 'div',
                'content' => ['test22222 {{', $callback, '}}']
            ]
        ];

        $tag = new Tag('div', $content);

        $this->assertTrue(true);
        $this->assertStringContainsString('{{string from callback function.}}', (string) $tag);
    }

    public function getTestString01()
    {
        return 'test 123';
    }

}
