<?php

namespace Ez\Ui\Form\Pair;

use Ez\Html;
use Ez\Ui\Form\Pair as FormPair;

/**
 * Class Select
 * @package Ez\Ui\Form\Pair
 * @author Derek Li
 */
class Select extends Html\Select
{
    /**
     * @var FormPair
     */
    protected $formPair = null;

    /**
     * @param FormPair $formPair
     * @return $this
     */
    public function stashFormPair(FormPair $formPair)
    {
        $this->formPair = $formPair;
        return $this;
    }

    /**
     * @return FormPair
     */
    public function revealFormPair()
    {
        $formPair = $this->formPair;
        $this->formPair = null;
        return $formPair;
    }

    /**
     * Finish the select creation.
     * Return the stored parent form pair
     * and remove the reference to the parent form pair.
     *
     * @return FormPair
     */
    public function finish()
    {
        return $this->revealFormPair();
    }
}