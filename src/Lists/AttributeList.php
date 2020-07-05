<?php

namespace Ht7\Html\Lists;

use \InvalidArgumentException;
use \Ht7\Base\Lists\HashList;
use \Ht7\Html\Attribute;
use \Ht7\Html\Utility\CanRenderList;
use \Ht7\Html\Renderable;

/**
 * Description of ItemList
 *
 * @author Thomas Plüss
 */
class AttributeList extends HashList implements \JsonSerializable, Renderable
{

    /**
     * Get the HTML representation of the current element with its descendants.
     *
     * @return  string                  A string of HTML elements.
     */
    public function __toString()
    {
        ksort($this->items);

        return implode(' ', $this->items);
    }

    /**
     * Add an attribute to the present AttributeList.
     *
     * Only instances of the Attribute class will be accepted.
     *
     * @Overridden
     */
    public function add($item)
    {
        if ($item instanceof Attribute) {
            parent::add($item);

            return $this;
        } else {
            $msg = 'The item must be an instance of the Attribute class, found %s.';
            $e = sprintf($msg, gettype($item));

            throw new InvalidArgumentException($e);
        }
    }

    /**
     * Add an attribute name and its value to the present AttributeList.
     *
     * @param   string  $name           The attribute name.
     * @param   string  $value          The attribute value.
     * @return  AttributeList           The present AttributeList instance.
     */
    public function addPlain($name, $value)
    {
        parent::add((new Attribute($name, $value)));

        return $this;
    }

    /**
     * Check wheter a value can be found or not in the present AttributeList instance.
     *
     * @param   string  $compare        The search value.
     * @return  boolean                 True if an attribute with the present
     *                                  search value could be found.
     */
    public function hasByValue($compare)
    {
        /* @var $attr Attribute */
        foreach ($this->items as $attr) {
            if ($attr->getValue() === $compare) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $items = [];
        $all = $this->getAll();

        foreach ($all as $item) {
            $items[$item->getName()] = $item->getValue();
        }

        return $items;
    }

    /**
     * Load the present AttributeList instance with the submitted data.
     *
     * @Overridden
     */
    public function load(array $data)
    {
        foreach ($data as $key => $value) {
            if ($value instanceof Attribute) {
                $this->add($value);
            } else {
                if (is_integer($key) || empty($key)) {
                    $key = $value;
                    $value = '';
                }

                $this->addPlain($key, $value);
            }
        }
    }

}
