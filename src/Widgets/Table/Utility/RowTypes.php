<?php

namespace Ht7\Html\Widgets\Table\Utility;

use \Ht7\Base\Enum;

/**
 * Description of RowTypes
 *
 * @author Thomas Pluess
 */
class RowTypes extends Enum
{

    /**
     * Specify the current row as a body row.
     */
    const BODY = 1;

    /**
     * Specify the current row as a footer row.
     */
    const FOOTER = 2;

    /**
     * Specify the current row as a header row.
     */
    const HEADER = 3;

    public static final function getRowType($type)
    {
        switch ($type) {
            case static::FOOTER:
            case 'foot':
            case 'footer':
            case 'tfoot':
                return static::FOOTER;
            case static::HEADER:
            case 'head':
            case 'header':
            case 'thead':
                return static::HEADER;
            case static::BODY:
            case 'body':
            case 'tbody':
            default:
                return static::BODY;
        }
    }

    public static final function getRowTypeName(integer $type)
    {
        switch ($type) {
            case static::FOOTER:
                return 'footer';
            case static::HEADER:
                return 'header';
            case static::BODY:
            default:
                return 'body';
        }
    }

    public static final function getRowTypeTagName($type)
    {
        $typeTransformed = static::getRowType($type);

        switch ($typeTransformed) {
            case static::FOOTER:
                return 'tfoot';
            case static::HEADER:
                return 'thead';
            case static::BODY:
            default:
                return 'tbody';
        }
    }

}
