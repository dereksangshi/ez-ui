<?php

namespace Ez\Ui\Form;

use Ez\Html;

/**
 * Class ResetButton
 * @package Ez\Ui\Form
 * @author Derek Li
 */
class ResetButton extends Html\Button
{
    public function __construct($label = 'Reset')
    {
        parent::__construct('reset', $label);
    }
}