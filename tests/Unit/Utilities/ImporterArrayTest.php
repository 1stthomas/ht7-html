<?php

namespace Ht7\Html\Tests\Unit\Tags;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Tag;
use \Ht7\Html\Text;
use \Ht7\Html\Utilities\ImporterArray;

class ImporterArrayTest extends TestCase
{

    /**
     * @var ImporterArray
     */
    protected $importer;

    public function setUp()
    {
        parent::setUp();

        $this->importer = ImporterArray::getInstance();
    }

    public function testCreateContentElement()
    {
        $this->assertInstanceOf(Text::class, $this->importer->createContentElement('Some text...'));
        $this->assertInstanceOf(Text::class, $this->importer->createContentElement(new Text('Some text 2...')));
        $this->assertInstanceOf(Tag::class, $this->importer->createContentElement(new Tag('div')));

        $mock = $this->getMockBuilder(ImporterArray::class)
                ->setMethods(['read'])
                ->getMock();

        $mock->expects($this->once())
                ->method('read')
                ->willReturn('from mock');

        $this->assertEquals('from mock', $mock->createContentElement(['test']));
    }

    public function testGetInstance()
    {
        $this->assertInstanceOf(ImporterArray::class, ImporterArray::getInstance());
    }

    public function testReadGivenTagSingleContent()
    {
        $input = [
            'tag' => 'button',
            'attributes' => [
                'class' => 'btn btn-default'
            ],
            'content' => 'Button Text'
        ];

        $mock = $this->getMockBuilder(ImporterArray::class)
                ->setMethods(['createContentElement'])
                ->getMock();

        $mock->expects($this->once())
                ->method('createContentElement')
                ->with('Button Text')
                ->willReturn(new Text('Button Text'));

        $tag = $mock->read($input);

        $this->assertInstanceOf(Tag::class, $tag);
        $this->assertEquals('button', $tag->getTagName());
        $this->assertInstanceOf(Text::class, $tag->getContent()->get(0));
    }

    public function testReadWithEmptyInput()
    {
        $this->assertNull($this->importer->createContentElement([]));
        $this->assertNull($this->importer->createContentElement([]), new Tag('div'));
    }

    public function testReadWithExceptionByMissingTagName()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->importer->read(['attributes' => ['style' => 'display: none;']]);
    }

    public function testReadWithExceptionByNonArray()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->importer->read('throws exception');
    }

}
