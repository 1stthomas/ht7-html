<?php

namespace Ht7\Html\Tags;

use \Ht7\Html\Utilities\AbstractSourceContainer;

/**
 * This class generates the HTML markup of a select tag with a few input.
 *
 * To use it, just instanciate this class with the desired image, source set and
 * attribute informations:<br />
 * <code>
 * $img = ['src' => 'image.jpg', 'alt' => '', 'class' => 'img img-responsive'];<br />
 * $sets = [690 => 'image1.png', 1100 => 'image2.png'];<br />
 * $pic = new Picture($img, $sets, ['class' => 'test-123']);<br />
 * echo $pic;<br />
 * </code><br />
 *
 * @author 1stthomas
 */
class Picture extends AbstractSourceContainer
{

    /**
     * Create an instance of the Picture class.
     *
     * @param   array   $img            The img tag attribute definitions.
     * @param   array   $srcSets        The srcset attribute definitions.
     * @param   array   $attributes     Indexed array of Attribute instances, or
     *                                  assoc array with the attribute names as
     *                                  keys and their values as values.
     */
    public function __construct($img = [], $srcSets = [], $attributes = [])
    {
        $content = $this->translateSrcSets($srcSets);
        $content[] = $this->translateImg($img);

        parent::__construct('picture', $content, $attributes);
    }

    /**
     * Create the img tag specific informations.
     *
     * @param   array   $img            Img tag attribute definitions.
     * @return  array                   The img tag definitions.
     */
    protected function translateImg(array $img = [])
    {
        return ['attributes' => $img, 'content' => [], 'tag' => 'img'];
    }

    /**
     * {@inheritdoc}
     */
    protected function translateSrcSets(array $srcSets = [])
    {
        $content = [];

        foreach ($srcSets as $width => $src) {
            $content[] = [
                'attributes' => [
                    'media' => '(min-width:' . $width . 'px)',
                    'srcset' => $src
                ],
                'tag' => 'source'
            ];
        }

        return $content;
    }

}
