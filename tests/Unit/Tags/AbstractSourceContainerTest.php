<?php

namespace Ht7\Html\Tests\Unit\Tags;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Tag;
use \Ht7\Html\Tags\AbstractSourceContainer;

class AbstractSourceContainerTest extends TestCase
{

    public function testConstructor()
    {
        $mock = $this->getMockForAbstractClass(AbstractSourceContainer::class);

        $this->assertInstanceOf(Tag::class, $mock);
    }

}
