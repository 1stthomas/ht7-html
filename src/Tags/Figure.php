<?php

namespace Ht7\Html\Tags;

use \Ht7\Html\Tag;

//use \Ht7\Html\Tags\SourceContainer;

/**
 * This class generates the HTML markup of a figure tag with a picture and a figcaption.
 *
 * To use it, just instanciate this class with the desired image, source set and
 * attribute informations, like following:<br />
 * <code>
 * $img = ['src' => 'image.jpg', 'alt' => '', 'class' => 'img img-responsive'];<br />
 * $sets = [690 => 'image1.png', 1100 => 'image2.png'];<br />
 * $caption = 'Lovely Picture';<br />
 * $pic = new Figure($img, $caption, $sets, ['class' => 'test-123']);<br />
 * </code><br />
 *
 * @author 1stthomas
 * @link https://stackoverflow.com/questions/12899691/use-of-picture-inside-figure-element-in-html5
 */
class Figure extends Tag
{

    /**
     * Create an instance of the Picture class.
     *
     * @param   array   $img            The img tag attribute definitions.
     * @param   array   $caption        The figcaption tag content.
     * @param   array   $srcSets        The srcset attribute definitions.
     * @param   array   $attributes     Indexed array of Attribute instances, or
     *                                  assoc array with the attribute names as
     *                                  keys and their values as values. These
     *                                  attributes will be appended to the figure tag.
     */
    public function __construct($img = [], $caption = [], $srcSets = [], $attributes = [])
    {
        $picture = new Picture($img, $srcSets, $attributes);
        $figcap = new Tag('figcaption', $caption);

        parent::__construct('figure', [$picture, $figcap], $attributes);
    }

}
