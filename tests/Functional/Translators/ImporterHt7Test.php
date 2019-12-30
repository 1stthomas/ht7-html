<?php

namespace Ht7\Base\Tests\Translators;

use \DOMDocument;
use \InvalidArgumentException;
use \PHPUnit\Framework\TestCase;
use \Ht7\Base\Exceptions\InvalidDatatypeException;
use \Ht7\Html\Translators\ImporterHt7;

/**
 * Test class for the ImporterHt7 class.
 *
 * @author      Thomas Pluess
 * @since       0.0.1
 * @version     0.0.1
 * @copyright (c) 2019, Thomas Pluess
 */
class ImporterHt7Test extends TestCase
{

    public function testGetAttributeArrayFromDom()
    {
        $doc = new DOMDocument();
        $div = $doc->createElement('div');
        $div->setAttribute('class', 'btn btn-default');
        $div->setAttribute('data-test', 'test-data-123');

        $attributes = ImporterHt7::getAttributeArrayFromDom($div);

        $this->assertArrayHasKey('class', $attributes);
        $this->assertArrayHasKey('data-test', $attributes);

        $this->assertContains('btn btn-default', $attributes);
        $this->assertContains('test-data-123', $attributes);
    }

    public function testGetAttributeListFromDom()
    {
        $doc = new DOMDocument();
        $div = $doc->createElement('div');
        $div->setAttribute('class', 'btn btn-default');
        $div->setAttribute('data-test', 'test-data-123');

        $attributes = ImporterHt7::getAttributeListFromDom($div);

        $this->assertTrue($attributes->has('class'));
        $this->assertTrue($attributes->has('data-test'));

        $this->assertTrue($attributes->hasByValue('btn btn-default'));
        $this->assertTrue($attributes->hasByValue('test-data-123'));
    }

    /**
     * @dataProvider getDefinitionArrays
     */
    public function testReadFromArray(array $data)
    {
        if (!empty($data['test']) && empty($data['test']['tag'])) {
            $this->expectException(InvalidArgumentException::class);
        }

        $html = ImporterHt7::readFromArray($data['test']);

        if (empty($data['test'])) {
            $this->assertNull($html);
        } else {
            foreach ($data['expected'] as $item) {
                $this->assertStringContainsString($item, $html);
            }
        }
    }

    /**
     * @dataProvider getDefinitionStrings
     */
    public function testReadFromHtmlString($stringHtml)
    {
        $str = ImporterHt7::readFromHtmlString($stringHtml);

        $this->assertEquals($stringHtml, $str);
    }

    /**
     * @dataProvider getDefinitionStringsInvalid
     */
    public function testReadFromInvalidHtmlString(array $data)
    {
        if (isset($data['exception'])) {
            $this->expectException($data['exception']);
        }

        $str = ImporterHt7::readFromHtmlString($data['test']);

        $this->{$data['method']}($str);

//
//        $str = ImporterHt7::readFromHtmlString('Normal text.');
//        $this->assertEmpty($str);
//
//        $this->expectException(InvalidDatatypeException::class);
//        ImporterHt7::readFromHtmlString(new \stdClass());
    }

    public function getDefinitionArrays()
    {
        return [
            'test1' => [
                [
                    'expected' => [
                        '<div class="text-holder" id="text-holder-123">',
                        'A simple text. Another simple text.',
                        '</div>'
                    ],
                    'test' => [
                        'tag' => 'div',
                        'content' => [
                            'A simple text.',
                            ' ',
                            'Another simple text.'
                        ],
                        'attributes' => [
                            'class' => 'text-holder',
                            'id' => 'text-holder-123'
                        ]
                    ]
                ]
            ],
            'test2' => [
                [
                    'expected' => [
                        '<div class="btn" id="btn-123">',
                        'simple text<span><span class="',
                        '</span><span class="innerst-2nd">Im the second.</span></span></div>'
                    ],
                    'test' => [
                        'tag' => 'div',
                        'content' => [
                            'simple text',
                            [
                                'tag' => 'span',
                                'content' => [
                                    [
                                        'tag' => 'span',
                                        'content' => ['Im the first.'],
                                        'attributes' => ['class' => 'innerst-first']
                                    ],
                                    [
                                        'tag' => 'span',
                                        'content' => ['Im the second.'],
                                        'attributes' => ['class' => 'innerst-2nd']
                                    ]
                                ]
                            ]
                        ],
                        'attributes' => ['class' => 'btn', 'id' => 'btn-123']
                    ]
                ]
            ],
            'test_empty' => [
                [
                    'test' => []
                ]
            ],
            'test_exception' => [
                [
                    'test' => [
                        'content' => [
                            'Missing tag key should throw an exception.'
                        ]
                    ]
                ]
            ]
        ];
    }

    public function getDefinitionStrings()
    {
        return [
            'from_root_html' => [
                '<html lang="de"><body><div class="test1" id="test-123"><span>text</span><div class="test1" id="test-456">test1</div></div><div>test2</div></body></html>'
            ],
            'head' => [
                '<head><title>Test 1</title><meta charset="utf8" /></head>'
            ],
            'body' => [
                '<body><section><h1>Part 1</h1><p>content part 1</p></section><section><h1>Part 2</h1><p>content part 2</p></section></body>'
            ]
        ];
    }

    public function getDefinitionStringsInvalid()
    {
        return [
            'normal_text' => [
                [
                    'test' => 'Normal text.',
                    'exception' => InvalidArgumentException::class
                ]
            ],
            'wrong_datatype' => [
                [
                    'test' => new \stdClass(),
                    'exception' => InvalidDatatypeException::class
                ]
            ]
        ];
    }

}
