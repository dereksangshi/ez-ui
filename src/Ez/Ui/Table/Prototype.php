<?php

namespace Ez\Ui\Table;

use Ez\Html;

/**
 * Class Prototype
 * @package Ez\Ui\Table
 * @author Derek Li
 */
class Prototype extends Html\Div
{
    /**
     * @var array Table contents.
     */
    protected $contents = array();

    /**
     * @var array Table columns.
     */
    protected $columns = array();

    /**
     * @var array The decoration decorations.
     */
    protected $decorations = array();

    /**
     * Add a decoration callback to a selector (event).
     *
     * @param $selector
     * @param $callable
     * @return $this
     */
    public function addDecoration($selector, $callable)
    {
        if (!array_key_exists($selector, $this->decorations)) {
            $this->decorations[$selector] = array();
        }
        array_push($this->decorations[$selector], $callable);
        return $this;
    }

    /**
     * Get the decorations of a certain selector (event).
     *
     * @param $selector
     * @return mixed
     */
    public function getDecorations($selector = null)
    {
        if (!isset($selector)) {
            return $this->decorations;
        }
        if (array_key_exists($selector, $this->decorations)) {
            return $this->decorations[$selector];
        }
    }

    /**
     * Add decoration callback to bottom toolbar.
     *
     * @param $callable
     * @param string $when
     * @return $this
     */
    public function addDecorationToTopToolbar($callable, $when = 'after')
    {
        $this->addDecoration($this->getSelector('topToolbar', $when), $callable);
        return $this;
    }

    /**
     * Add decoration callback to bottom toolbar.
     *
     * @param $callable
     * @param string $when
     * @return $this
     */
    public function addDecorationToBottomToolbar($callable, $when = 'after')
    {
        $this->addDecoration($this->getSelector('bottomToolbar', $when), $callable);
        return $this;
    }

    /**
     * Get the name of the selector (event).
     *
     * @param $where
     * @param string $when
     * @return string
     */
    public function getSelector($where, $when = 'after')
    {
        return sprintf('%s:%s', $where, $when);
    }

    /**
     * Add decoration callback to
     * <thead>
     *     <tr>
     *
     * @param $callable
     * @param string $when
     * @return $this
     */
    public function addDecorationToTheadTr($callable, $when = 'after')
    {
        $this->addDecoration($this->getSelector('thead tr', $when), $callable);
        return $this;
    }

    /**
     * Add decoration callback to
     * <thead>
     *     <tr>
     *         <th>
     *
     * @param $callable
     * @param string $when
     * @return $this
     */
    public function addDecorationToTheadTrTh($callable, $when = 'after')
    {
        $this->addDecoration($this->getSelector('thead tr th', $when), $callable);
        return $this;
    }

    /**
     * Add decoration callback to
     * <tbody>
     *     <tr>
     *
     * @param $callable
     * @param string $when
     * @return $this
     */
    public function addDecorationTbodyTr($callable, $when = 'after')
    {
        $this->addDecoration($this->getSelector('tbody tr', $when), $callable);
        return $this;
    }

    /**
     * Add decoration callback to
     * <tbody>
     *     <tr>
     *         <td>
     *
     * @param $callable
     * @param string $when
     * @return $this
     */
    public function addDecorationToTbodyTrTd($callable, $when = 'after')
    {
        $this->addDecoration($this->getSelector('tbody tr td', $when), $callable);
        return $this;
    }

    /**
     * Call the decoration callbacks when it reaches the selector (event).
     *
     * @param $where
     * @param string $when
     */
    public function decorate($where, $when = 'after')
    {
        $decorations = $this->getDecorations($this->getSelector($where, $when));
        if (isset($decorations) && is_array($decorations)) {
            $methodArgs = func_get_args();
            array_shift($methodArgs);
            array_shift($methodArgs);
            foreach ($decorations as $d) {
                if (is_callable($d)) {
                    call_user_func_array($d, $methodArgs);
                }
            }
        }
    }

    /**
     * Add table contents.
     *
     * @param array $contents
     * @param null $selector OPTIONAL 'thead', 'tbody', 'tfoot'.
     * @return $this
     */
    public function addContents(array $contents, $selector = null)
    {
        if (!isset($selector)) {
            $this->contents = $contents;
        } else {
            $this->contents[$selector] = $contents;
        }
        return $this;
    }

    /**
     * Get table contents.
     *
     * @param null $selector OPTIONAL 'thead', 'tbody', 'tfoot'.
     * @return array
     */
    public function getContents($selector = null)
    {
        if (!isset($selector)) {
            return $this->contents;
        }
        if (array_key_exists($selector, $this->contents)) {
            return $this->contents[$selector];
        }
        return array();
    }

    /**
     * Set table columns.
     * The table will exactly show the columns here no matter the contents.
     *
     * @param $columns
     * @return $this
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * Get the columns.
     *
     * @return array
     */
    public function getColumns()
    {
        /**
         * Lazy load columns form 'thead'.
         */
        if (count($this->columns) === 0) {
            $header = $this->get('thead');
            if (count($header) > 0) {
                foreach ($this->header as $tHead) {
                    $this->setColumns(array_keys($tHead));
                    break;
                }
            }
        }
        return $this->columns;
    }

    /**
     * Draw the table prototype.
     *
     * @param array $tHeadContents
     * @param array $tBodyContents
     * @param array $tFootContents
     */
    public function draw(array $tHeadContents, array $tBodyContents, array $tFootContents = array())
    {
        $this->addContents($tHeadContents, 'thead');
        $this->addContents($tBodyContents, 'tbody');
        $this->addContents($tFootContents, 'tfoot');
        $topToolbar = new Html\Div();
        $topToolbar->attr(array('class' => 'BIT-TT'));
        $this->decorate('topToolbar', 'after', $topToolbar);
        $this->add($topToolbar);
        $tableWrapper = new Html\Div();
        $tableWrapper->attr(array('class' => 'BIT-TW'));
        $table = new Html\Table();
        $this->decorate('table', 'after', $table);
        $tableWrapper->add($table);
        $this->add($tableWrapper);
        $this->drawThead($table);
        $this->drawTbody($table);
        $this->drawTfoot($table);
        $bottomToolbar = new Html\Div();
        $bottomToolbar->attr(array('class' => 'BIT-BT'));
        $this->add($bottomToolbar);
        $this->decorate('bottomToolbar', 'after', $bottomToolbar);
    }

    /**
     * Draw <thead></thead>
     *
     * @param $table
     * @return Html\Thead
     */
    protected function drawThead($table)
    {
        $tHead = new Html\Thead();
        $table->add($tHead);
        foreach ($this->getContents('thead') as $rowIndex => $rowContent) {
            $this->drawTheadTr($tHead, $rowIndex, $rowContent);
        }
        return $tHead;
    }

    /**
     * Draw <thead>
     *          <tr></tr>
     *      </thead>
     *
     * @param Html\Thead $tHead
     * @param $rowIndex
     * @param $rowContent
     * @return Html\Tr
     */
    protected function drawTheadTr(Html\Thead $tHead, $rowIndex, $rowContent)
    {
        $tr = new Html\Tr();
        $tr->attr('tr-id', $rowIndex);
        $this->decorate('thead tr', 'before', $tr, $rowIndex, $rowContent);
        $tHead->add($tr);
        $columns = $this->getColumns();
        foreach ($columns as $c) {
            $thContent = array_key_exists($c, $rowContent) ? $rowContent[$c] : null;
            $this->drawTheadTrTh($tr, $rowIndex, $c, $thContent);
        }
        $this->decorate('thead tr', 'after', $tr, $rowIndex, $rowContent);
        return $tr;
    }

    /**
     * Draw <thead>
     *          <tr>
     *              <th></th>
     *          </tr>
     *      </thead>
     *
     * @param Html\Tr $tr
     * @param $rowIndex
     * @param $thId
     * @param $thContent
     * @return Html\Th
     */
    protected function drawTheadTrTh(Html\Tr $tr, $rowIndex, $thId, $thContent)
    {
        $th = new Html\Th();
        $th->attr('column-id', $thId);
        $this->decorate('thead tr th', 'before', $th, $rowIndex, $thId, $thContent);
        $tr->add($th);
        $th->add($thContent);
        $this->decorate('thead tr th', 'after', $th, $rowIndex, $thId, $thContent);
        return $th;
    }

    /**
     * Draw <tbody></tbody>
     *
     * @param $table
     * @return Html\Tbody
     */
    protected function drawTbody($table)
    {
        $tBody = new Html\Tbody();
        $table->add($tBody);
        $i = 0;
        foreach ($this->getContents('tbody') as $rowIndex => $rowContent) {
            $i++;
            $tr = $this->drawTbodyTr($tBody, $rowIndex, $rowContent);
            if ($i % 2 == 1) {
                $tr->addClass('odd');
            } else {
                $tr->addClass('even');
            }
        }
        return $tBody;
    }

    /**
     * Draw <tbody>
     *          <tr></tr>
     *      </tbody>
     *
     * @param Html\Tbody $tBody
     * @param $rowIndex
     * @param $rowContent
     * @return Html\Tr
     */
    protected function drawTbodyTr(Html\Tbody $tBody, $rowIndex, $rowContent)
    {
        $tr = new Html\Tr();
        $tr->attr('tr-id', $rowIndex);
        $this->decorate('tbody tr', 'before', $tr, $rowIndex, $rowContent);
        $tBody->add($tr);
        $columns = $this->getColumns();
        foreach ($columns as $c) {
            $tdContent = array_key_exists($c, $rowContent) ? $rowContent[$c] : null;
            $this->drawTbodyTrTd($tr, $rowIndex, $c, $tdContent);
        }
        $this->decorate('tbody tr', 'after', $tr, $rowIndex, $rowContent);
        return $tr;
    }

    /**
     * Draw <tbody>
     *          <tr>
     *              <td></td>
     *          </tr>
     *      </tbody>
     *
     * @param Html\Tr $tr
     * @param $rowIndex
     * @param $tdId
     * @param $tdContent
     * @return Html\Td
     */
    protected function drawTbodyTrTd(Html\Tr $tr, $rowIndex, $tdId, $tdContent)
    {
        $td = new Html\Td();
        $td->attr('column-id', $tdId);
        $this->decorate('tbody tr td', 'before', $td, $rowIndex, $tdId, $tdContent);
        $tr->add($td);
        $td->add($tdContent);
        $this->decorate('tbody tr td', 'after', $td, $rowIndex, $tdId, $tdContent);
        return $td;
    }

    protected function drawTfoot($table)
    {

    }
}