<?php

namespace Ez\Ui\Tests;

use Ez\Ui\Table as UiTable;
use Ez\Ui\Table\Component as UiTableComponent;

/**
 * Class TableTest
 *
 * @package Ez\Ui\Tests
 * @author Derek Li
 */
class TableTest extends \PHPUnit_Framework_TestCase
{
    public function testTableCreation()
    {
        $uiTable = new UiTable();
        $columns = array('b', 'd', 'e', 'f');
        $theadContents = array(
            0 => array(
                'a' => 'AAAAA',
                'b' => 'BBBBB',
                'c' => 'CCCCC',
                'd' => 'DDDDD',
                'e' => 'EEEEE',
            )
        );
        $tbodyContents = array(
            1 => array(
                'a' => 'A1',
                'b' => 'B1',
                'c' => 'C1',
                'd' => 'D1',
                'e' => 'E1'
            ),
            3 => array(
                'a' => 'A3',
                'b' => 'B3',
                'e' => 'E3',
                'c' => 'C3',
                'd' => 'D3',
            )
        );
        $sortableColumns = array('a', 'd', 'b');
        $orderByColumn = 'd';
        $orderByDir = 'desc';
        $totalRows = 203;
        $rowsPerPage = 30;
        $currentPage = 4;
        $numOfPageButtons = 6;
        $uiTable
            ->addComponent(
                'ColumnSorting',
                function (UiTableComponent\ColumnSorting $component) use (
                    $sortableColumns,
                    $orderByColumn,
                    $orderByDir
                ) {
                    $component->setSortableColumns($sortableColumns);
                    if (isset($orderByColumn)) {
                        $component->setOrderByColumn($orderByColumn, $orderByDir);
                    }
                }
            )
            ->addComponent(
                'Paging',
                function (UiTableComponent\Paging $component) use (
                    $totalRows,
                    $rowsPerPage,
                    $currentPage,
                    $numOfPageButtons
                ) {
                    $component
                        ->setTotalRows($totalRows)
                        ->setRowsPerPage($rowsPerPage)
                        ->setCurrentPage($currentPage)
                        ->setNumOfPageButtons($numOfPageButtons);
                }
            )
            ->getTablePrototype()
                ->setColumns($columns)
                ->draw($theadContents, $tbodyContents);
        $this->assertInstanceOf('\Ez\Ui\Table', $uiTable, 'new UiTable() should return an instance of \Ez\Ui\Table');
    }
}

