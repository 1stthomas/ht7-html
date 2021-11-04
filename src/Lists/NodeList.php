<?php

namespace Ht7\Html\Lists;

use \Ht7\Html\Utilities\ImporterArray;

/**
 * The NodeList class is a list of <code>\Ht7\Html\Node</code> instances.
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
        parent::add(ImporterArray::getInstance()->import($item));

        return $this;
    }

    /**
     * Serialize the object to a value that can beserialized natively by <code>json_encode</code>.
     *
     * To recieve a complete array representation of this list, use
     * <code>json_decode(json_encode($nodeList), JSON_OBJECT_AS_ARRAY)</code>.
     * This is because the json serialization on the inner object will only be
     * triggered by the PHP internal serialization.
     *
     * @return  array               An array representation of this instance.
     */
    public function jsonSerialize()
    {
//        $items = [];
//        $all = $this->getAll();
//
//        foreach ($all as $item) {
//            $items[] = $item->jsonSerialize();
//        }
//
//        return $items;
        return $this->getAll();
    }

}
