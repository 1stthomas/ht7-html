<?php

namespace Ht7\Html\Utilities;

use \Ht7\Html\Tag;
use \Ht7\Html\Utilities\AbstractImporter;
use \Ht7\Html\Utilities\ImporterDom;

/**
 * This class can transform several kinds of tag definitions into a ht7 tag tree.
 *
 * @author Thomas Plüss
 */
class ImporterString extends AbstractImporter
{

    /**
     * Get the starting tag of the present HTML string.
     *
     * @param   string  $html           The HTML string to search the starting
     *                                  tag.
     * @return  string|null             A string of the found tag name, or null
     *                                  if no tag could be found.
     */
    public function getStartingTag($html)
    {
        $matches = [];

        preg_match('/^<\s*([a-zA-Z]+)[^>]*>/', $html, $matches);

        return count($matches) > 1 ? $matches[1] : null;
    }

    /**
     * Search for a specific tag in a HTML string.
     *
     * @param   string  $html           The haystack.
     * @param   string  $tag            The HTML tag to find.
     * @return  boolean                 True if the tag in question could be found.
     */
    public function hasTagInString($html, $tag)
    {
        return preg_match("/\s*?" . $tag . "\b[^>]*>/", $html) === 1 ? true : false;
    }

    /**
     * Get a Tag representation of the html in the present string.
     *
     * This function uses internally the DOMDocument and its anchestors to gain
     * a valid HTML DOM. This Dom is parsed to gain the Tag tree.
     *
     * @param   string  $html               A string with a valid HTML structure.
     * @return  Tag                         The Tag tree.
     * @throws  \InvalidArgumentException
     */
    public function read($html, Tag $tag = null)
    {
        if (!is_string($html)) {
            $e = 'The input source must be a string, found '
                    . (is_object($html) ? get_class() : gettype($html)) . '.';

            throw new \InvalidArgumentException($e);
        } else {
            $html = trim($html);

            if (strpos($html, '<') !== 0) {
                throw new \InvalidArgumentException();
            }
        }

        $els = [];
        $dom = new \DOMDocument();
        $dom->loadHTML($html);

        if ($this->hasTagInString($html, 'html')) {
            $els = $dom->getElementsByTagName('html');
        } else {
            $tag = $this->getStartingTag($html);

            if (!empty($tag)) {
                $els = $dom->getElementsByTagName($tag);
            }
        }

        return count($els) > 0 ? ImporterDom::getInstance()->read($els[0]) : null;
    }

}
