<?php

namespace Ht7\Html\Tests\Unit\Tags;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Utilities\AbstractImporter;

class AbstractImporterTest extends TestCase
{

    public function testGetInstance()
    {
        $this->expectException(\BadMethodCallException::class);

        AbstractImporter::getInstance();
    }

}
