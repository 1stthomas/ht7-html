<?php

namespace Ht7\Html\Tests\Unit\Tags;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Utilities\AbstractImporter;
use \Ht7\Html\Utilities\ImporterArray;

class AbstractImporterTest extends TestCase
{

    public function testGetInstance()
    {
        $this->assertInstanceOf(ImporterArray::class, ImporterArray::getInstance());
    }

    public function testGetInstanceWithClass()
    {
        $this->assertInstanceOf(ImporterArray::class, AbstractImporter::getInstance(ImporterArray::class));
    }

    public function testGetInstanceWithException()
    {
        $this->expectException(\BadMethodCallException::class);

        AbstractImporter::getInstance();
    }

    public function testSetGetInstance()
    {
        AbstractImporter::setInstance(new ImporterArray());

        $this->assertInstanceOf(ImporterArray::class, AbstractImporter::getInstance(ImporterArray::class));
    }

    public function testSetGetInstanceWithClass()
    {
        AbstractImporter::setInstance(new ImporterArray(), ImporterArray::class);

        $this->assertInstanceOf(ImporterArray::class, AbstractImporter::getInstance(ImporterArray::class));
    }

}
