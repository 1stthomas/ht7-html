<?php

namespace Ht7\Html\Models;

/**
 * Model for the <code>\Ht7\Html\Utilities\ImporterArray<code> class.
 */
class ImporterArrayDefaultsModel
{

    protected $callback;

    public function __construct(array $data = [])
    {
        if (!empty($data['callback'])) {
            $this->setCallback($data['callback']);
        }
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

}
