<?php

namespace Ez\Ui;

use Ez\Html\Div;
use Ez\Html\Ul;
use Ez\Html\Li;
use Ez\Html\Span;

/**
 * Class HashList
 * @package Ez\Ui
 * @author Derek Li
 */
class HashList extends Div
{
    /**
     * The hash (data).
     *
     * @var array
     */
    protected $hash = array();

    /**
     * If the ul has been drawn.
     *
     * @var bool
     */
    protected $isDrawn = false;

    /**
     * Constructor.
     *
     * @param array $hash The given hash.
     */
    public function __construct(array $hash = null)
    {
        if (isset($hash)) {
            $this->hash($hash);
        }
    }

    public function hash(array $hash = null)
    {
        if (!isset($hash)) {
            return $this->hash;
        } else {
            $this->hash = $hash;
        }
    }

    /**
     * Draw the HTML ul based on the given data.
     *
     */
    public function draw()
    {
        $hash = $this->hash();
        if (is_array($hash)) {
            $rootUl = new Ul();
            $rootUl->attr('class', 'hl-ul');
            $this->add($rootUl);
            $this->_drawRecursively($rootUl, $hash);
        }
        $this->isDrawn = true;
    }

    /**
     * Recursively create a Ul based on the given hash (array).
     *
     * @param Ul $ul The ul container.
     * @param mixed $children The children.
     */
    protected function _drawRecursively($ul, $children)
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
                    $this->_drawRecursively($subUl, $subChildren);
                } else {
                    $this->_addChild($li, $subChildren, $label);
                }
            }
        } else {
            $li = new Li();
            $ul->add($li);
            $this->_addChild($li, $children);
        }
    }

    /**
     * Add child (key-value pair or just the single value) to the list.
     *
     * @param Li $li The list to add to.
     * @param mixed $content The content to add.
     * @param null|mixed $label OPTIONAL the label to add.
     */
    protected function _addChild($li, $content, $label = null)
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
    }

    /**\
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