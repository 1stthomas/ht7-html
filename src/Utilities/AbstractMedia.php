<?php

namespace Ht7\Html\Utilities;

/**
 * Base class of <code>\Ht7\Html\Tags\Video</code> and <code>\Ht7\Html\Tags\Audio</code>.
 *
 * This class provides 2 methods to translate the input data into \Ht7\Html classes
 * usable definitions.
 *
 * @author 1stthomas
 */
abstract class AbstractMedia extends AbstractSourceContainer
{

    /**
     * {@inheritdoc}
     */
    protected function translateSrcSets(array $srcSets = [])
    {
        $content = [];

        foreach ($srcSets as $type => $src) {
            $content[] = [
                'attributes' => [
                    'src' => $src,
                    'type' => $type
                ],
                'tag' => 'source'
            ];
        }

        return $content;
    }

    /**
     * Translate the track definitions into ht7-html readable data.
     *
     * @param   array   $tracks     Indexed array of track tag attributes definitions.
     * @return  array               Indexed array of track tags definitions.
     */
    protected function translateTracks(array $tracks = [])
    {
        $content = [];

        foreach ($tracks as $track) {
            $content[] = [
                'attributes' => $track,
                'tag' => 'track'
            ];
        }

        return $content;
    }

    protected function setUp($srcSets = [], $tracks = [], $text = [])
    {
        $sets = $this->translateSrcSets($srcSets);
        $trs = $this->translateTracks($tracks);

        $content = array_merge($sets, $trs);

        if (!empty($text)) {
            $content[] = $text;
        }

        return $content;
    }

}
