<?php

namespace Ht7\Html\Iterators;

use \Iterator;
use \Ht7\Html\Tag;
use \Ht7\Html\Text;

/**
 * This iterator goes through all Node element of the starting Tag by pre order
 * iteration.
 *
 * Pre order: output the root first, then the left, then the right subtree.
 * In order: output the left subtree first, then the root, then the right one.
 * Post order: output the left subtree first, then the right one, followed by the root.
 * @see: https://lvb-wissen.de/informatik/datenstrukturen/binearbaum/traversierungsverfahren:-Preorder-Inorder-und-Postorder-8592095901.html
 *
 * @author Thomas Plüss
 */
class PreOrderIterator implements Iterator
{

    /**
     *
     * @var     array       Indexed array of NodeLists.
     */
    protected $children;

    /**
     *
     * @var     Tag         The current tag of the loop.
     */
    protected $current;

    /**
     *
     * @var     array       Indexed array of parent Tags.
     */
    protected $parents;

    /**
     *
     * @var     array       Indexed array of positions as integers starting from 0.
     */
    protected $position;

    /**
     *
     * @var     Tag         The starting Tag of the current loop.
     */
    protected $tag;

    /**
     * Create an instance of the VerticalIterator class.
     *
     * @param   Tag     $tag        The starting tag of the current loop.
     */
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    /**
     * Get the element of the current iteration step.
     *
     * @return  mixed
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Get the key of the current iteration step.
     *
     * @return  integer         This is the position relative to the parent of
     *                          the current element.
     */
    public function key()
    {
        return $this->getPosition();
    }

    /**
     * Go a level up, if there is in minimum one further Node. Otherwise go down
     * until the next element can be found.
     */
    public function next()
    {
        if ($this->current instanceof Tag) {
            $position = $this->getPosition();

            if ($this->children[count($this->children) - 1]->has($position)) {
                $this->goLevelUp();
            } elseif (count($this->parents) > 0) {
                $this->goLevelDown();
            } else {
                // The starting Tag has been found. Make sure, the iteration stops.
                $this->position = [];
            }
        } elseif ($this->current instanceof Text) {
            // These Nodes can not have content objects.
            $this->goLevelDown();
        } else {
            // @todo: handle other kind of Nodes.
        }
    }

    /**
     * Reset the iterator.
     */
    public function rewind()
    {
        $this->position = [0];
        $this->current = $this->tag;
        $this->children = [];
        $this->parents = [];

        $this->children[] = $this->current->getContent();
    }

    /**
     * Check for a valid element. Otherwise stop the iteration.
     *
     * @return  boolean             False if the iteration has to be stopped.
     */
    public function valid()
    {
        return count($this->position) !== 0;
    }

    /**
     * Get the current position.
     *
     * @return  integer             The position of the new element if present.
     */
    protected function getPosition()
    {
        return $this->position[count($this->position) - 1];
    }

    /**
     * Return to the next parent Node element.
     */
    protected function goLevelDown()
    {
        $this->current = $this->parents[count($this->parents) - 1];

        array_pop($this->children);
        array_pop($this->parents);
        array_pop($this->position);

        $this->next();
    }

    /**
     * Go up to the next Node element.
     */
    protected function goLevelUp()
    {
        $this->parents[] = $this->current;
        $this->current = $this->children[count($this->children) - 1]
                ->get($this->getPosition());
        $this->inkrement();
        $this->children[] = $this->current instanceof Tag ? $this->current->getContent() : null;
        $this->position[] = 0;
    }

    /**
     * Inkrement the current position.
     */
    protected function inkrement()
    {
        $this->position[count($this->position) - 1]++;
    }

}
