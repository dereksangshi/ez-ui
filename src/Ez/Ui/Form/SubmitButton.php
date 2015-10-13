<?php

namespace Ez\Ui\Form;

use Ez\Html;

/**
 * Class SubmitButton
 * @package Ez\Ui\Form
 * @author Derek Li
 */
class SubmitButton extends Html\Button
{
    public function __construct($label = 'Submit')
    {
        parent::__construct('submit', $label);
    }
}