<?php

namespace Ez\Ui;

use Ez\Html\Div;
use Ez\Html\Ul;
use Ez\Html\Li;
use Ez\Html\Span;

/**
 * Class ArrayList
 *
 * @package Ez\Ui
 * @author Derek Li
 */
class ArrayList extends Div
{
    /**
     * The array (data).
     *
     * @var array
     */
    protected $array = array();

    /**
     * If the ul has been drawn.
     *
     * @var bool
     */
    protected $isDrawn = false;

    /**
     * Constructor.
     *
     * @param array $array The given array.
     */
    public function __construct(array $array = null)
    {
        if (isset($array)) {
            $this->setArray($array);
        }
    }

    /**
     * @param array|null $array
     * @return $this
     */
    public function setArray(array $array = null)
    {
        $this->array = $array;
        return $this;
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return $this->array;
    }

    /**
     * Draw the HTML ul based on the given data.
     *
     * @return $this
     */
    public function draw()
    {
        $array = $this->getArray();
        if (is_array($array)) {
            $rootUl = new Ul();
            $rootUl->attr('class', 'hl-ul');
            $this->add($rootUl);
            $this->drawRecursively($rootUl, $array);
        }
        $this->isDrawn = true;
        return $this;
    }

    /**
     * Recursively create a Ul based on the given array (array).
     *
     * @param Ul $ul The ul container.
     * @param mixed $children The children.
     */
    protected function drawRecursively($ul, $children)
    {
        if (is_array($children)) {
            foreach ($children as $label => $subChildren) {
                $li = new Li();
                $li->attr('class', 'hl-li');
                $ul->add($li);
                if (is_array($subChildren)) {
                    $div = new Div();
                    $div->attr('class', 'hl-node');
                    $div->add($label);
                    $li->add($div);
                    $subUl = new Ul();
                    $subUl->attr('class', 'hl-sub-ul');
                    $li->add($subUl);
                    $this->drawRecursively($subUl, $subChildren);
                } else {
                    $this->addChild($li, $subChildren, $label);
                }
            }
        } else {
            $li = new Li();
            $ul->add($li);
            $this->addChild($li, $children);
        }
    }

    /**
     * Add child (key-value pair or just the single value) to the list.
     *
     * @param Li $li The list to add to.
     * @param mixed $content The content to add.
     * @param null|mixed $label OPTIONAL the label to add.
     * @return $this
     */
    protected function addChild($li, $content, $label = null)
    {
        if ($label) {
            $labelSpan = new Span();
            $labelSpan->attr('class', 'hl-label')
                ->add($label);
            $li->add($labelSpan);
        }
        $contentSpan = new Span();
        $contentSpan->attr('class', 'hl-content')
            ->add($content);
        $li->add($contentSpan);
        return $this;
    }

    /**
     * Override parent method.
     * Return the html.
     *
     * @return string
     */
    public function __toString()
    {
        if (!$this->isDrawn) {
            $this->draw();
        }
        return parent::__toString();
    }
}