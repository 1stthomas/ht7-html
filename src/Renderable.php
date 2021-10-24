<?php

namespace Ht7\Html;

/**
 * Indicator to show its content is printable and will produce valid HTML markup.
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
