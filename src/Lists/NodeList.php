<?php

namespace Ht7\Html\Lists;

use \Ht7\Base\Exceptions\InvalidDatatypeException;
use \Ht7\Html\Node;
use \Ht7\Html\Text;
use \Ht7\Html\Translators\ImporterHt7;

/**
 * Description of ItemList
 *
 * @author Thomas Plüss
 */
class NodeList extends AbstractRenderableList
{

    /**
     * @Overriden
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
            throw new InvalidDatatypeException('The content', $item, ['scalar', 'array'], [Node::class]);
        }

        return $this;
    }

    public function load(array $data)
    {
        foreach ($data as $item) {
            $this->add($item);
        }
    }

}
