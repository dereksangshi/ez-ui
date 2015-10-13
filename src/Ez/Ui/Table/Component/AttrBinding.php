<?php

namespace Ez\Ui\Table\Component;

use Ez\Ui\Table\Prototype as TablePrototype;
use Ez\Html;

/**
 * Class AttrBinding
 * @package Ez\Ui\Table\Component
 * @author Derek Li
 */
class AttrBinding implements ComponentInterface
{
    /**
     * All the custom attributes are here.
     *
     * @var array
     */
    protected $attributes = array();

    /**
     * @param array $attributes
     * @param null $selector
     * @return $this
     */
    public function setAttributes(array $attributes, $selector = null)
    {
        if (!isset($selector)) {
            $this->attributes = $attributes;
        } else {
            $this->attributes[$selector] = $attributes;
        }
        return $this;
    }

    /**
     * @param null $selector
     * @return array
     */
    public function getAttributes($selector = null)
    {
        if (!isset($selector)) {
            return $this->attributes;
        }
        if (array_key_exists($selector, $this->attributes)) {
            return $this->attributes[$selector];
        }
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setTableAttributes(array $attributes)
    {
        return $this->setAttributes($attributes, 'table');
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setTopToolbarAttributes(array $attributes)
    {
        return $this->setAttributes($attributes, 'topToolbar');
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setBottomToolbarAttributes(array $attributes)
    {
        return $this->setAttributes($attributes, 'bottomToolbar');
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setTheadTrAttributes(array $attributes)
    {
        return $this->setAttributes($attributes, 'thead tr');
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setTheadTrThAttributes(array $attributes)
    {
        return $this->setAttributes($attributes, 'thead tr th');
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setTbodyTrAttributes(array $attributes)
    {
        return $this->setAttributes($attributes, 'tbody tr');
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setTbodyTrTdAttributes(array $attributes)
    {
        return $this->setAttributes($attributes, 'tbody tr td');
    }

    /**
     * Bind attributes to the html element.
     *
     * @param array $attributes Attributes to bind.
     * @param Html\Element $htmlElement
     * @return $this
     */
    public function bindAttributes(array $attributes, Html\Element $htmlElement)
    {
        foreach ($attributes as $attr => $val) {
            $htmlElement->attr($attr, $val);
        }
        return $this;
    }

    /**
     * Bind attributes in the callbacks.
     *
     * @param TablePrototype $tablePrototype
     * @return $this
     */
    public function decorate(TablePrototype $tablePrototype)
    {
        // For PHP 5.3.*
        $self = $this;
        $attributes = $self->getAttributes();
        foreach ($attributes as $selector => $attrs) {
            if ($selector == 'table' ||
                $selector == 'topToolbar' ||
                $selector == 'bottomToolbar'
            ) {
                $tablePrototype->addDecoration(
                    $tablePrototype->getSelector($selector, 'after'),
                    function (Html\Element $htmlElement) use ($self, $attrs) {
                        $self->bindAttributes($attrs, $htmlElement);
                    }
                );
            } else if ($selector == 'thead tr' || $selector == 'tbody tr') {
                $tablePrototype->addDecoration(
                    $tablePrototype->getSelector($selector, 'after'),
                    function (Html\Element $htmlElement, $rowIndex) use ($self, $attrs) {
                        if (array_key_exists($rowIndex, $attrs)) {
                            $self->bindAttributes($attrs[$rowIndex], $htmlElement);
                        }
                    }
                );
            } else if ($selector == 'thead tr th' || $selector == 'tbody tr td') {
                $tablePrototype->addDecoration(
                    $tablePrototype->getSelector($selector, 'after'),
                    function (Html\Element $htmlElement, $rowIndex, $col) use ($self, $attrs) {
                        if (array_key_exists($rowIndex, $attrs) &&
                            is_array($attrs[$rowIndex]) &&
                            array_key_exists($col, $attrs[$rowIndex])
                        ) {
                            $self->bindAttributes($attrs[$rowIndex][$col], $htmlElement);
                        }
                    }
                );
            }
        }
        return $this;
    }
}