<?php

namespace Ht7\Base\Tests;

use \PHPUnit\Framework\TestCase;
use \Ht7\Html\Tag;
use \Ht7\Html\Text;

/**
 * Description of PreOrderIteratorTest
 *
 * @author Thomas Pluess
 */
class PreOrderIteratorTest extends TestCase
{

    public function testForEach()
    {
        $content = [
            new Tag('span', ['testtest..'], []),
            new Tag('p', ['testtest..'], []),
            new Text('testtest text..'),
            new Tag('p', ['testtest..'], [])
        ];
        $tag = new Tag('div', $content, ['class' => 'testclass']);
        $sequence = [
            Tag::class,
            Tag::class,
            Text::class,
            Tag::class,
            Text::class,
            Text::class,
            Tag::class,
            Text::class,
        ];
        $i = 0;

        foreach ($tag as $el) {
            $this->assertInstanceOf($sequence[$i], $el);
            $i++;
        }

        $content2 = [
            new Tag('span', ['testtest..'], []),
            new Tag('p', [(new Tag('span', ['testtest 2a..'], ['class' => 'innerst'])), 'testtest 2b..'], []),
            new Text('testtest text..'),
            new Tag('p', ['testtest..'], [])
        ];
        $tag2 = new Tag('div', $content2, ['class' => 'testclass']);
        $sequence2 = [
            Tag::class,
            Tag::class,
            Text::class,
            Tag::class,
            Tag::class,
            Text::class,
            Text::class,
            Text::class,
            Tag::class,
            Text::class,
        ];
        $i2 = 0;

        foreach ($tag2 as $el) {
            $this->assertInstanceOf($sequence2[$i2], $el);
            $i2++;
        }

        $it = $tag2->getIteratorPreOrder();
        $this->assertEquals(0, $it->key());
        foreach ($it as $el) {

        }
        $this->assertEquals(0, $it->key());
    }

}
