<?php

namespace Ht7\Html\Tests\Unit\Tags;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Callback;
use \Ht7\Html\Replacer;
use \Ht7\Html\Tag;
use \Ht7\Html\Text;
use \Ht7\Html\Lists\NodeList;
use \Ht7\Html\Models\ImporterArrayDefaultsModel;
use \Ht7\Html\Utilities\ImporterArray;

final class ImporterArrayTest extends TestCase
{

    /**
     * @var ImporterArray
     */
    protected $importer;

    public function setUp()
    {
        parent::setUp();


        $this->importer = new ImporterArray();
        ImporterArray::setInstance($this->importer);
    }

    public function testCreateTag()
    {
        $this->assertInstanceOf(Tag::class, $this->importer->createTag(['tag' => 'div']));
    }

    public function testCreateTagExceptionByEmptyArray()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->importer->createTag([]);
    }

    public function testCreateTagExceptionByMissingTag()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->importer->createTag(['content' => ['test']]);
    }

    public function testCreateTagExceptionByWrongParameter()
    {
        $this->expectException(\TypeError::class);

        $this->importer->createTag(new \stdClass);
    }

    public function testCreateText()
    {
        $this->assertInstanceOf(Text::class, $this->importer->createText('text content'));
        $this->assertInstanceOf(Text::class, $this->importer->createText(123));
        $this->assertInstanceOf(Text::class, $this->importer->createText(123.45));
    }

    public function testCreateTextExceptionByWrongParameter()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->importer->createText(new \stdClass);
    }

    public function testCreateTypedElementCallback()
    {
        $data = [
            'method' => 'test',
            'type' => 'callback',
        ];

        $this->assertInstanceOf(Callback::class, $this->importer->createTypedElement($data));
    }

    public function testCreateTypedElementReplacerWithArray()
    {
        $data = [
            'id' => '123',
            'type' => 'replacer',
            'callback' => [
                'method' => 'test',
            ],
        ];

        $mock = $this->getMockBuilder(ImporterArray::class)
                ->setMethods(['getDefaults'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->never())
                ->method('getDefaults');

        $this->assertInstanceOf(Replacer::class, $mock->createTypedElement($data));
    }

    public function testCreateTypedElementReplacerWithDefaults()
    {
        $data = [
            'id' => '123',
            'type' => 'replacer',
        ];

        $defaults = $this->getMockBuilder(ImporterArrayDefaultsModel::class)
                ->setMethods(['getCallback'])
                ->disableOriginalConstructor()
                ->getMock();
        $defaults->expects($this->once())
                ->method('getCallback')
                ->willReturn(['id' => '123', 'method' => 'test']);

        $mock = $this->getMockBuilder(ImporterArray::class)
                ->setMethods(['getDefaults'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('getDefaults')
                ->willReturn($defaults);

        $this->assertInstanceOf(Replacer::class, $mock->createTypedElement($data));
    }

    public function testCreateTypedElementWithExceptionByWrongType()
    {
        $data = [
            'type' => 'tag',
        ];

        $this->expectException(\InvalidArgumentException::class);

        $this->importer->createTypedElement($data);
    }

    public function testCreateTypedElementWithExceptionByWrongDatatype()
    {
        $this->expectException(\TypeError::class);

        $this->importer->createTypedElement(new \stdClass);
    }

    public function testGetDefaults()
    {
        $className = ImporterArray::class;

        $defaults = $this->createMock(ImporterArrayDefaultsModel::class);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['import'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new \ReflectionClass($className);
        $property = $reflectedClass->getProperty('defaults');
        $property->setAccessible(true);
        $property->setValue($mock, $defaults);

        $this->assertEquals($defaults, $mock->getDefaults());
    }

    public function testGetInstance()
    {
        $this->assertInstanceOf(ImporterArray::class, ImporterArray::getInstance());
    }

    public function testImportEmpty()
    {
        $this->assertNull($this->importer->import([]));
    }

    public function testImportIndexedArray()
    {
        $val = [
            'text content',
            123.45,
            new \stdClass,
            ['inner content'],
        ];

        $mock = $this->getMockBuilder(ImporterArray::class)
                ->setMethods(['importNodeList', 'isIndexed'])
                ->getMock();

        $mock->expects($this->once())
                ->method('importNodeList')
                ->with($val)
                ->willReturn(new NodeList());
        $mock->expects($this->once())
                ->method('isIndexed')
                ->with($val)
                ->willReturn(true);

        $this->assertInstanceOf(NodeList::class, $mock->import($val));
    }

    public function testImportNodeList()
    {
        $this->assertInstanceOf(NodeList::class, $this->importer->importNodeList([
                    'string', 123], true));
    }

    public function testImportNodeListReturnArray()
    {
        $this->assertContainsOnlyInstancesOf(Text::class, $this->importer->importNodeList([
                    'string', 123]));
    }

    public function testImportNodeListEmpty()
    {
        $this->assertInstanceOf(NodeList::class, $this->importer->importNodeList([
                        ], true));
    }

    public function testImportNodeListEmptyReturnArrayEmpty()
    {
        $this->assertEquals([], $this->importer->importNodeList([]));
    }

    public function testImportNodeTag()
    {
        $node = $this->createMock(Tag::class);

        $this->assertEquals($node, $this->importer->import($node));
    }

    public function testImportNodeText()
    {
        $node = $this->createMock(Text::class);

        $this->assertEquals($node, $this->importer->import($node));
    }

    public function testImportText()
    {
        $vals = [
            'text content',
            123,
            123.45,
            ['tag' => 'div'],
        ];

        $mock = $this->getMockBuilder(ImporterArray::class)
                ->setMethods(['createText', 'isIndexed'])
                ->getMock();

        $mock->expects($this->exactly(3))
                ->method('createText')
                ->withConsecutive([$vals[0]], [$vals[1]], [$vals[2]]);
        $mock->expects($this->once())
                ->method('isIndexed')
                ->with(['tag' => 'div'])
                ->willReturn(false);

        foreach ($vals as $value) {
            $mock->import($value);
        }
    }

    public function testImportTypedArray()
    {
        $val = [
            'type' => 'test',
        ];

        $mock = $this->getMockBuilder(ImporterArray::class)
                ->setMethods(['createTypedElement', 'isIndexed'])
                ->getMock();

        $mock->expects($this->once())
                ->method('createTypedElement')
                ->with($val);
        $mock->expects($this->once())
                ->method('isIndexed')
                ->with($val)
                ->willReturn(false);

        $mock->import($val);
    }

    public function testImportWithExceptionByMissingTagName()
    {
        $this->expectException(\InvalidArgumentException::class);

        $val = ['attributes' => ['style' => 'display: none;']];

        $mock = $this->getMockBuilder(ImporterArray::class)
                ->setMethods(['isIndexed'])
                ->getMock();

        $mock->expects($this->once())
                ->method('isIndexed')
                ->with($val)
                ->willReturn(false);

        $mock->import($val);
    }

    public function testImportWithExceptionByNodeList()
    {
        $this->expectException(\InvalidArgumentException::class);

        $val = new NodeList();

        $this->importer->import($val);
    }

    public function testImportWithExceptionByStdclass()
    {
        $this->expectException(\InvalidArgumentException::class);

        $val = new \stdClass();

        $this->importer->import($val);
    }

    public function testIsIndexed()
    {
        $mock = $this->getMockBuilder(ImporterArray::class)
                ->setMethods(['import'])
                ->getMock();

        $reflectedClass = new \ReflectionClass(ImporterArray::class);
        $method = $reflectedClass->getMethod('isIndexed');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($mock, ['test', 123, 123.45]));
        $this->assertTrue($method->invoke($mock, ['test', 123, 123.45]));
        $this->assertFalse($method->invoke($mock, ['test' => 123, 123.45]));
        $this->assertFalse($method->invoke($mock, [1 => 123, 2 => 123.45]));
    }

}
