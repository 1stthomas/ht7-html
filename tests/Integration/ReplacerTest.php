<?php

namespace Ht7\Base\Tests\Integration;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Callback;
use \Ht7\Html\Replacer;
use \Ht7\Html\Utilities\ImporterArray;

class ReplacerTest extends TestCase
{

    public function testLifecycle()
    {
        $content = [
            'tag' => 'div',
            'attributes' => ['class' => 'container'],
            'content' => [
                'text',
                [
                    'type' => 'replacer',
                    'id' => 'replacer-test-123',
                    'callback' => [
                        'instance' => $this,
                        'method' => 'callbackTest01',
                        'parameters' => ['content' => 'text from definitions'],
                    ]
                ]
            ]
        ];

        $html = (string) ImporterArray::getInstance()->import($content);

        $this->assertStringStartsWith('<div', $html);
        $this->assertStringEndsWith('</div>', $html);
        $this->assertStringContainsString('text from definitions - text from method', $html);
        $this->assertStringContainsString('replacer-test-123', $html);
    }

    public function callbackTest01(array $parameters)
    {
        return $parameters['content'] . ' - text from method.' . $parameters['id'];
    }

}
