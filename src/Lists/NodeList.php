<?php

namespace Ht7\Html\Lists;

use \Ht7\Base\Exceptions\InvalidDatatypeException;
use \Ht7\Html\Node;
use \Ht7\Html\Tag;
use \Ht7\Html\Text;
use \Ht7\Html\Translators\ImporterHt7;

/**
 * The NodeList class is a list of <code>\Ht7\Html\Tag</code> and
 * <code>\Ht7\Html\Text</code> instances.
 *
 * @author Thomas Plüss
 */
class NodeList extends AbstractRenderableList implements \JsonSerializable
{

    /**
     * {@inheritdoc}
     */
    public function add($item)
    {
        if ($item instanceof Node) {

            parent::add($item);
        } elseif (is_array($item)) {

            parent::add(ImporterHt7::readFromArray($item));
        } elseif (is_string($item) || is_integer($item) || is_float($item)) {

            parent::add((new Text($item)));
        } else {
            print_r(json_encode($this));
            throw new InvalidDatatypeException('The content', $item, ['scalar', 'array'], [Node::class]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $items = [];
        $all = $this->getAll();

        foreach ($all as $item) {
            if ($item instanceof Text) {
                $items[] = $item->getContent();
            } elseif ($item instanceof Tag) {
                $items[] = $item->jsonSerialize();
            }
        }

        return $items;
    }

}
