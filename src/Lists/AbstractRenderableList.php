<?php

namespace Ht7\Html\Lists;

use Ht7\Base\Lists\ItemList;
use Ht7\Html\Utilities\CanRenderList;
use Ht7\Html\Renderable;

/**
 * Description of AbstractItemList
 *
 * @author Thomas Plüss
 */
abstract class AbstractRenderableList extends ItemList implements Renderable
{

    use CanRenderList;
}
