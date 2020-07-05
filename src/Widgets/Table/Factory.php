<?php

namespace Ht7\Html\Widgets\Table;

use \Ht7\Html\Widgets\Table\Table as Controller;
use \Ht7\Html\Widgets\Table\Models\Simple as ModelSimple;
use \Ht7\Html\Widgets\Table\Utility\RowTypes;

/**
 * This factory
 *
 * @author Thomas Pluess
 */
class Factory
{

    /**
     * Create a simple table widget with arrays of body, header and footer rows.
     *
     * @param   array       $body       The body rows. This can be an array with
     *                                  arrays of rows or an array with cells.
     * @param   array       $header     The header rows. This can be an array with
     *                                  arrays of rows or an array with cells.
     * @param   array       $footer     The footer rows. This can be an array with
     *                                  arrays of rows or an array with cells.
     * @return  Controller
     */
    public static function createSimple(array $body, array $header = [], array $footer = [])
    {
        $data = [
            RowTypes::HEADER => $header,
            RowTypes::BODY => $body,
            RowTypes::FOOTER => $footer
        ];

        $model = new ModelSimple($data);

        return new Controller($model);
    }

}
