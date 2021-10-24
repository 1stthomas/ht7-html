<?php

namespace Ht7\Html\Utilities;

use \Ht7\Html\Tag;
use \Ht7\Html\Text;
use \Ht7\Html\Utilities\AbstractImporter;

/**
 * This class can transform several kinds of tag definitions into a ht7 tag tree.
 *
 * @author Thomas Plüss
 */
class ImporterArray extends AbstractImporter
{

    /**
     * Create a Node instance according to the datatype of the present element.
     *
     * @param   mixed   $el             The present element on which is decided
     *                                  what has to be done next.
     *                                  If it is a scalar value, a Text instance
     *                                  will be created. Otherwise a next
     *                                  recursive iteration will be released.
     * @return  mixed                   Text or Tag instance according to the
     *                                  datatype of the present element.
     */
    public function createContentElement($el)
    {
        if (is_scalar($el)) {
            return new Text($el);
        } elseif ($el instanceof Tag || $el instanceof Text) {
            return $el;
        } else {
            return $this->read($el);
        }
    }

    /**
     * Build a tag tree from an array.
     *
     * E.g.
     * <pre><code>
     * $arr = [
     *     'tag' => 'div',
     *     'content' => ['simple text'],
     *     'attributes' => ['class' => 'btn', 'id' => 'btn-123']
     * ];
     * </code></pre>
     * will produce <code>&lt;div class="btn" id="btn-123"&gt;simple text&lt;&#47;div&gt;</code>
     *
     * @param   array   $arr
     * @return  Tag
     * @throws  \InvalidArgumentException
     */
    public function read($arr, Tag $tag = null)
    {
        if (empty($arr)) {
            return null;
        } elseif (!is_array($arr)) {
            $e = 'The input must be an array, found ' . gettype($arr);

            throw new \InvalidArgumentException($e);
        } elseif (isset($arr['tag'])) {
            $attributes = isset($arr['attributes']) ? $arr['attributes'] : [];
            $els = isset($arr['content']) ? $arr['content'] : [];
            $content = [];

            if (is_array($els)) {
                foreach ($els as $el) {
                    $content[] = $this->createContentElement($el);
                }
            } else {
                $content[] = $this->createContentElement($els);
            }

            if (empty($tag)) {
                return new Tag($arr['tag'], $content, $attributes);
            } else {
                $tag->setContent($content);
                $tag->setAttributes($attributes);

                return $tag;
            }
        } else {
            $e = 'The "tag" definition is missing.';

            throw new \InvalidArgumentException($e);
        }
    }

}
