<?php

namespace Ez\Ui\Table\Component;

use Ez\Ui\Table\Prototype as TablePrototype;
use Ez\Html\Div;
use Ez\Html\Span;
use Ez\Html\A;
use Ez\Html\Input;

/**
 * Class ColumnSorting
 * @package Ez\Ui\Table\Component
 * @author Derek Li
 */
class Paging implements ComponentInterface
{
    const CURRENT_PAGE = '_p';

    /**
     * How many rows totally?
     *
     * @var int
     */
    protected $totalRows = 0;

    /**
     * How many rows per page?
     *
     * @var int
     */
    protected $rowsPerPage = 20;

    /**
     * The current page.
     *
     * @var int
     */
    protected $currentPage = 1;

    /**
     * How many page buttons to display?
     *
     * @var int
     */
    protected $numOfPageButtons = 5;

    /**
     * @var null
     */
    protected $paging = null;

    /**
     * @param $numOfRows
     * @return $this
     */
    public function setTotalRows($numOfRows)
    {
        $this->totalRows = $numOfRows;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalRows()
    {
        return $this->totalRows;
    }

    /**
     * @param $numOfRowsPerPage
     * @return $this
     */
    public function setRowsPerPage($numOfRowsPerPage)
    {
        $this->rowsPerPage = $numOfRowsPerPage;
        return $this;
    }

    /**
     * @return int
     */
    public function getRowsPerPage()
    {
        return $this->rowsPerPage;
    }

    /**
     * @param $currentPage
     * @return $this
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function setNumOfPageButtons($numOfPageButtons)
    {
        $this->numOfPageButtons = $numOfPageButtons;
        return $this;
    }

    public function getNumOfPageButtons()
    {
        return $this->numOfPageButtons;
    }

    /**
     * @return null
     */
    public function getPaging()
    {
        if (!isset($this->paging)) {
            $this->drawPaging();
        }
        return $this->paging;
    }

    /**
     * Add paging to toolbar.
     *
     * @param $toolbar
     */
    public function addPaging($toolbar)
    {
        $toolbar->add($this->getPaging());
    }

    /**
     * Draw the paging.
     *
     * @return $this
     */
    protected function drawPaging()
    {
        $totalPages = ceil($this->getTotalRows() / $this->getRowsPerPage());
        $numOfPageButtons = $this->getNumOfPageButtons();
        $currentPage = $this->getCurrentPage();
        $currentPage = ($currentPage >= 1) ? $currentPage : 1;
        $halfPage = (int)(($numOfPageButtons - 1) / 2);
        $startPage = (($currentPage - $halfPage) >= 1) ? $currentPage - $halfPage : 1;
        $finishPage = $lastPage = (($startPage + $numOfPageButtons - 1) <= $totalPages) ?
            $startPage + $numOfPageButtons - 1 :
            $totalPages;
        $startPage = $lastPage - $numOfPageButtons + 1 > 0 ? $lastPage - $numOfPageButtons + 1 : 1;
        // Draw paging
        $paging = new Div();
        $paging->attr(array('class' => 'BIT-E-P'));
        // Extra info
        $extraInfo = new Div();
        $extraInfo->attr(array('class' => 'E-INFO'));
        $totalResults = new Span();
        $totalResults->attr(array('class' => 'TOTAL-PAGE'));
        $totalResults->add(sprintf("Results | %d", $this->getTotalRows()));
        $extraInfo->add($totalResults);
        $paging->add($extraInfo);
        // Buttons.
        $pageMenuWrapper = new Div();
        $pageMenuWrapper->addClass('P-MENU-WRAPPER');
        $pageMenu = new Div();
        $pageMenuWrapper->add($pageMenu);
        $pageMenu->attr(array('class' => 'P-MENU'));
        $paging->add($pageMenuWrapper);
        $firstPage = new A();
        $reloadParams = sprintf("{'%s':'%d'}", self::CURRENT_PAGE, 1);
        $firstPage->attr(array(
            'class' => 'FIRST MOV',
            'page' => 1,
            'onclick' => "jQuery.ezpjaxtable.ezTableReload({$reloadParams})",
            'params' => $reloadParams
        ));
        $firstPage->add('|<');
        $pageMenu->add($firstPage);
        $previousPage = new A();
        $previousPage->attr(array('class' => 'PRE MOV'));
        $prePage = $currentPage - 1 > 0 ? $currentPage - 1 : 1;
        if ($prePage != $currentPage) {
            $reloadParams = sprintf("{'%s':'%d'}", self::CURRENT_PAGE, $prePage);
            $previousPage
                ->attr('page', $prePage)
                ->attr('onclick', "jQuery.ezpjaxtable.ezTableReload({$reloadParams})")
                ->attr('params', $reloadParams);
        }
        $previousPage->add('<');
        $pageMenu->add($previousPage);
        for ($i = $startPage; $i <= $finishPage; $i++) {
            $pager = new A();
            $reloadParams = sprintf("{'%s':'%d'}", self::CURRENT_PAGE, $i);
            $pager->attr(array(
                'page' => $i,
                'class' => 'P',
                'onclick' => "jQuery.ezpjaxtable.ezTableReload({$reloadParams})",
                'params' => $reloadParams
            ));
            if ($i == $currentPage) {
                $pager->addClass('ACTIVE');
            }
            $pager->add($i);
            $pageMenu->add($pager);
        }
        $nextPage = new A();
        $nextPage->attr(array('class' => 'NEXT MOV'));
        $nexPage = $currentPage + 1 > $totalPages ? $totalPages : $currentPage + 1;
        if ($nexPage != $currentPage) {
            $reloadParams = sprintf("{'%s':'%d'}", self::CURRENT_PAGE, $nexPage);
            $nextPage
                ->attr('page', $nexPage)
                ->attr('onclick', "jQuery.ezpjaxtable.ezTableReload({$reloadParams})")
                ->attr('params', $reloadParams);
        }
        $nextPage->add('>');
        $pageMenu->add($nextPage);
        $lastPage = new A();
        $lastPage->attr(array('class' => 'LAST MOV', 'page' => $totalPages));
        $reloadParams = sprintf("{'%s':'%d'}", self::CURRENT_PAGE, $totalPages);
        $lastPage
            ->attr('onclick', "jQuery.ezpjaxtable.ezTableReload({$reloadParams})")
            ->attr('params', $reloadParams)
            ->add('>|');
        $pageMenu->add($lastPage);
        // To page
        $pageJump = new Div();
        $pageState = new Span();
        $pageState->attr(array('class' => 'STATE'));
        $pageState->add("{$currentPage}/{$totalPages}");
        $pageJump->add($pageState);
        $pageJump->attr(array('class' => 'P-JUMP'));
        $toPage = new Input();
        $toPage->attr(array('class' => 'TOPAGE', 'ng-model' => 'toPage'));
        $pageJump->add($toPage);
        $goButton = new Span();
        $goButton->attr(array('class' => 'GO', 'onclick' => 'jQuery.ezpjaxtable.ezTableReloadToPage()'));
        $goButton->add('Go');
        $pageJump->add($goButton);
        $paging->add($pageJump);
        // Clear both.
        $clearDiv = new Div();
        $clearDiv->attr('class', 'clear');
        $paging->add($clearDiv);
        $this->paging = $paging;
        return $this;
    }

    /**
     * Add classes to make table columns sortable.
     *
     * @param TablePrototype $tablePrototype
     * @return mixed|void
     */
    public function decorate(TablePrototype $tablePrototype)
    {
        $tablePrototype->addDecorationToTopToolbar(array($this, 'addPaging'));
        $tablePrototype->addDecorationToBottomToolbar(array($this, 'addPaging'));
    }
}