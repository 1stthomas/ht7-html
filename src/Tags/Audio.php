<?php

namespace Ht7\Html\Tags;

use \Ht7\Html\Tags\AbstractMedia;

/**
 * This class generates the HTML markup of an audio tag with a few input.
 *
 * To use it, just instanciate this class with the desired source set, tracks and
 * audio attribute informations:
 *
 * <code>
 * $audioAttr = ['autoplay' => '', 'controls' => '', 'loop' => ''];<br />
 * $sets = ['audio/ogg' => 'audio.ogg', 'audio/mpeg' => 'audio.mp3'];<br />
 * $audio = new Audio($audioAttr, $sets, [], ['Unsupported audio tag.']);<br />
 * echo $audio;<br />
 * </code><br />
 *
 * @author 1stthomas
 */
class Audio extends AbstractMedia
{

    /**
     * Create an instance of the Audio class.
     *
     * @param   array   $audio          The audio tag attribute definitions.
     * @param   array   $srcSets        The srcset attribute definitions.
     * @param   array   $tracks         The tracks attribute definitions.
     * @param   array   $text           Text to display if the audio tag is not
     *                                  support on the current client.
     */
    public function __construct($audio = [], $srcSets = [], $tracks = [], $text = '')
    {
        $content = $this->setUp($srcSets, $tracks, $text);

        parent::__construct('audio', $content, $audio);
    }

}
