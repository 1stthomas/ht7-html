<?php

namespace Ht7\Html\Utilities;

use \Ht7\Html\Tag;

/**
 * Base class for <code>\Ht7\Html\Tags\Picture</code> and <code>\Ht7\Html\Utilities\Media</code>,
 * which is extended by <code>\Ht7\Html\Tags\Audio</code> and <code>\Ht7\Html\Tags\Video</code>
 *
 * @author 1stthomas
 */
abstract class AbstractSourceContainer extends Tag
{

    /**
     * Translate the srcset definitions into ht7-html readable data.
     *
     * @param   array   $srcSets    Indexed array of source tag attributes definitions.
     * @return  array               Indexed array of source tags definitions.
     */
    abstract protected function translateSrcSets(array $srcSets = []);
}
