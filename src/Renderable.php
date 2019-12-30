<?php

namespace Ht7\Html;

/**
 *
 * @author Thonas Plüss
 */
interface Renderable
{

    /**
     * Get the HTML representation of the current element with its descendants.
     *
     * @return  string                  A string of HTML elements.
     */
    public function __toString();
}
