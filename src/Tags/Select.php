<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ht7\Html\Tags;

/**
 * Description of Select
 *
 * @author 1stthomas
 */
use \InvalidArgumentException;
use \Ht7\Html\Tag;

class Select extends Tag
{

    /**
     * Create an instance of the Select class.
     *
     * @param   mixed   $content        The content of the current select instance
     *                                  as string, int, float or indexed array of
     *                                  further Tag instances.
     * @param   array   $attributes     Indexed array of Attribute instances, or
     *                                  assoc array with the attribute names as
     *                                  keys and their values as values.
     */
    public function __construct($content = [], $attributes = [])
    {
        parent::__construct('select', $content, $attributes);
    }

    /**
     * Get the options of the current select element.
     *
     * @param   string      $outputType     'str' if possible Html-instances should
     *                                      be transformed into a string. Otherwise
     *                                      no trransformation will be performed.
     * @return  mixed                       The content of the current select element.
     */
    public function getContent($outputType = 'str')
    {
        $contentOrg = parent::getContent(false);

        foreach ($contentOrg as $option) {
            $attributes = empty($option['attributes']) ? [] : $option['attributes'];

            $tag = new Tag('option', $option['display'], $attributes);

            if ($outputType === 'str') {
                $content[] = '' . $tag;
            } else {
                $content[] = $tag;
            }
        }

        if ($outputType === 'str') {
            $content = implode('', $content);
        }

        return $content;
    }

    /**
     * Set the options of the current select element.
     *
     * @param   array   $content            Indexed array of assoc arrays. These
     *                                      assoc arrays represent the options and
     *                                      must have following keys: "display"
     *                                      (the display text of the option),
     *                                      "attributes" (assoc array of the
     *                                      option attributes)
     * @throws  InvalidArgumentException
     */
    public function setContent($content)
    {
        if (is_array($content)) {
            $this->content = $content;
        } else {
            $msg = 'The content must be an array, found %s.';
            $e = sprintf($msg, gettype($content));

            throw new InvalidArgumentException($e);
        }
    }

}
