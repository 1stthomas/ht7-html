<?php

namespace Ht7\Html\Tags;

use \Ht7\Html\Utilities\AbstractMedia;

/**
 * This class generates the HTML markup of an video tag with a few input.
 *
 * To use it, just instanciate this class with the desired source set, tracks and
 * video attribute informations:
 *
 * <code>
 * $videoAttr = ['width' => '500', 'height' => '320', 'autoplay' => '', 'controls' => '', 'loop' => ''];<br />
 * $sets = ['video/mp4' => 'movie.mp4', 'video/ogg' => 'movie.ogg'];<br />
 * $video = new Audio($videoAttr, $sets, [], ['Unsupported video tag.']);<br />
 * echo $video;<br />
 * </code><br />
 *
 * @author 1stthomas
 */
class Video extends AbstractMedia
{

    /**
     * Create an instance of the Video class.
     *
     * @param   array   $video          The video tag attribute definitions.
     * @param   array   $srcSets        The srcset attribute definitions.
     * @param   array   $tracks         The tracks attribute definitions.
     * @param   array   $text           Text to display if the video tag is not
     *                                  support on the current client.
     */
    public function __construct($video = [], $srcSets = [], $tracks = [], $text = '')
    {
        $content = $this->setUp($srcSets, $tracks, $text);

        parent::__construct('video', $content, $video);
    }

}
