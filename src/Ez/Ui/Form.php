<?php

namespace Ez\Ui;

use Ez\Html;
use Ez\Ui\Form as FormElement;

/**
 * Class Form
 * @package Ez\Ui
 * @author Derek Li
 */
class Form extends Html\Form
{
    /**
     * Constructor.
     *
     * @param string|null $action OPTIONAL
     * @param string $method OPTIONAL
     */
    public function __construct($action = null, $method = 'post')
    {
        if (!empty($action)) {
            $this->attr('action', $action);
        }
        if (!empty($method)) {
            $this->attr('method', $method);
        }
        $this->addClass('ez-ui-form');
    }

    /**
     * Create a form pair and add it to the form.
     *
     * @param null $callable
     * @return $this
     */
    public function addPair($callable = null)
    {
        $formPair = new FormElement\Pair();
        $formPair->addClass('pair');
        $this->add($formPair);
        if (is_callable($callable, true)) {
            call_user_func($callable, $formPair);
        }
        return $this;
    }

    /**
     * Add a form reset button.
     *
     * @param string $label
     * @return $this
     */
    public function addResetButton($label = 'Reset')
    {
        return $this->add(new FormElement\ResetButton($label));
    }

    /**
     * Add a form submit button.
     *
     * @param string $label
     * @return $this
     */
    public function addSubmitButton($label = 'Submit')
    {
        return $this->add(new FormElement\SubmitButton($label));
    }
}