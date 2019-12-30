<?php

namespace Ht7\Html\Models;

/**
 *
 *
 * @author Thomas Plüss
 */
final class SelfClosing
{

    /**
     * @var     array       Indexed array of self closing HTML tags.
     * @see                 https://developer.mozilla.org/en-US/docs/Glossary/Empty_element
     */
    private static $tags = [
        'area',
        'base',
        'br',
        'col',
        'embed',
        'hr',
        'img',
        'input',
        'keygen',
        'link',
        'meta',
        'param',
        'source',
        'track',
        'wbr'
    ];

    public static function add($tag)
    {
        self::$tags[] = $tag;
    }

    public static function expand(array $tags)
    {
        $arr1 = array_values($tags);
        $arr2 = array_flip(array_merge(self::$tags, $arr1));
        ksort($arr2);

        self::$tags = array_keys($arr2);
    }

    /**
     * Get a list of all self closing elements.
     *
     * @return  array           Indexed array of all self closing tag names.
     */
    public static function getAll()
    {
        return self::$tags;
    }

    /**
     * Whetever the present tag name is self closing or not.
     *
     * @return  boolean         True if the name is a self closing tag.
     */
    public static function is($name)
    {
        return in_array($name, self::$tags);
    }

}
