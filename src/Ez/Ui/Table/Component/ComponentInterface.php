<?php

namespace Ez\Ui\Table\Component;

use Ez\Ui\Table\Prototype as TablePrototype;

/**
 * Interface ComponentInterface
 * @package Ez\Ui\Table\Component
 * @author Derek Li
 */
interface ComponentInterface
{
    /**
     * Add decoration (callbacks) to table prototype.
     *
     * @param TablePrototype $tablePrototype
     * @return mixed
     */
    public function decorate(TablePrototype $tablePrototype);
}