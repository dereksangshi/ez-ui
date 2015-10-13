<?php

namespace Ez\Ui\Table\Component;

use Ez\Ui\Table\Prototype as TablePrototype;

/**
 * Class ColumnSorting
 * @package Ez\Ui\Table\Component
 * @author Derek Li
 */
class ColumnSorting implements ComponentInterface
{
    const ORDER_BY = '_ob';
    const ORDER_BY_DIR = '_obd';

    /**
     * @var array
     */
    protected $sortableColumns = array();

    /**
     * The column is currently sorted by.
     *
     * @var array
     */
    protected $sortByColumn = array();

    /**
     * Sorting direction ('asc' or 'desc')
     *
     * @var string
     */
    protected $sortByDir = 'asc';

    /**
     * Set sortable columns.
     *
     * @param array $columns
     * @return $this
     */
    public function setSortableColumns(array $columns)
    {
        $this->sortableColumns = $columns;
        return $this;
    }

    /**
     * Get sortable columns.
     *
     * @return array
     */
    public function getSortableColumns()
    {
        return $this->sortableColumns;
    }

    /**
     * @param $column
     * @param string $dir
     * @return $this
     */
    public function setOrderByColumn($column, $dir = 'asc')
    {
        $this->sortByColumn[$column] = $dir;
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderByColumn()
    {
        return $this->sortByColumn;
    }

    /**
     * @param $column
     * @return string
     */
    public function getOrderByDir($column)
    {
        if (array_key_exists($column, $this->sortByColumn)) {
            return $this->sortByColumn[$column];
        }
        return 'asc';
    }

    /**
     * If the column if sortable.
     *
     * @param string $column Name of the column.
     * @return bool
     */
    public function isColumnSortable($column)
    {
        return in_array($column, $this->sortableColumns);
    }

    public function addSortableColumn($column)
    {
        if (!in_array($column, $this->sortableColumns)) {
            array_push($this->sortableColumns, $column);
        }
        return $this;
    }

    /**
     * Add classes to make table columns sortable.
     *
     * @param TablePrototype $tablePrototype
     * @return $this
     */
    public function decorate(TablePrototype $tablePrototype)
    {
        // For PHP 5.3.*
        $self = $this;
        $tablePrototype->addDecorationToTheadTrTh(function ($th, $rowIndex, $thId) use ($self) {
            if ($self->isColumnSortable($thId)) {
                $th->addClass('sortable');
                if (array_key_exists($thId, $self->getOrderByColumn())) {
                    $th->addClass($self->getOrderByDir($thId));
                }
                $reloadParams = sprintf(
                    "{%s:'%s', %s: '%s'}",
                    ColumnSorting::ORDER_BY,
                    $thId,
                    ColumnSorting::ORDER_BY_DIR,
                    $self->getOrderByDir($thId) == 'desc' ? 'asc' : 'desc'
                );
                $th->attr('ng-click', "reloadTable({$reloadParams})");

            }
        });
        return $this;
    }
}