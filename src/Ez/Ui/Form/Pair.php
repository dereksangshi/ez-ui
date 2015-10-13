<?php

namespace Ez\Ui\Form;

use Ez\Html;
use Ez\Ui\Form as Form;
use Ez\Ui\Form\Pair\Select as PairSelect;

/**
 * Class Pair
 * @package Ez\Ui\Form
 * @author Derek Li
 */
class Pair extends Html\Div
{
    /**
     * Constructor.
     *
     * @param null $tag
     */
    public function __construct($tag = null)
    {
        parent::__construct($tag);
        $this->addClass('pair');
    }

    /**
     * Add a <label>.
     *
     * @param string $label
     * @param array $attributes
     * @return $this
     */
    public function addLabel($label, $for = null, $attributes = array())
    {
        $htmlLabel = new Html\Label($label, $for);
        if (count($attributes) > 0) {
            foreach ($attributes as $name => $val) {
                $htmlLabel->attr($name, $val);
            }
        }
        return $this->add($htmlLabel);
    }

    /**
     * Add an <input> into the form pair.
     *
     * @param string $type
     * @param string $name
     * @param string $value
     * @param mixed $attributes
     * @return $this
     */
    public function addInput($type = null, $name = null, $value = null, $attributes = array())
    {
        $htmlInput = new Html\Input($type, $name, $value);
        if (is_callable($attributes, true)) {
            call_user_func($attributes, $htmlInput);
        } else {
            if (is_array($attributes) && count($attributes) > 0) {
                foreach ($attributes as $name => $val) {
                    $htmlInput->attr($name, $val);
                }
            }
        }
        return $this->add($htmlInput);
    }

    /**
     * Create a <select> and add it to the form pair.
     *
     * @param mixed $attributes
     * @return PairSelect
     */
    public function addSelect($name = null, $attributes = array())
    {
//        $htmlSelect = new PairSelect($name);
        $htmlSelect = new Html\Select($name);
        if (is_callable($attributes, true)) {
            call_user_func($attributes, $htmlSelect);
        } else {
            if (is_array($attributes) && count($attributes) > 0) {
                foreach ($attributes as $name => $val) {
                    $htmlSelect->attr($name, $val);
                }
            }
        }
        $selectWrapper = new Html\Div();
        $selectWrapper->addClass('select-wrapper');
        $selectWrapper->add($htmlSelect);
        $this->add($selectWrapper);
        return $htmlSelect;
    }

    /**
     * Serve the magic call like 'addDiv()', 'addSpan()', ...
     * A full call should be like this:
     *      addDiv($attributes = array(), $child)
     *
     * @param $method
     * @param $args
     * @return $this
     * @throws \Exception
     */
    public function __call($method, $args)
    {
        if (preg_match('/^add([A-Z]{1}[a-zA-Z]*)$/', $method, $matches)) {
            $elementTag = lcfirst($matches[1]);
            // $args[0] should be the html element attributes which needs to be an array.
            if (!isset($args[0])) {
                $args[0] = array();
            }
            if (!is_array($args[0])) {
                $args[0] = array($args[0]);
            }
            $ele = Html\Factory::create($elementTag, $args[0]);
            // $args[1] should be the child of this html element.
            if (isset($args[1])) {
                $ele->add($args[1]);
            }
            $this->add($ele);
            return $this;
        } else {
            throw new \Exception(sprintf('Method "%s" does not exist. Called by class "%s".', $method, get_called_class()));
        }
    }
}