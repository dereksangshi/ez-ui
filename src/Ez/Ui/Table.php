<?php

namespace Ez\Ui;

use Ez\Html;
use Ez\Ui\Table\Prototype as TablePrototype;
use Ez\Ui\Table\Component as TableComponent;

/**
 * Class Table
 * @package Ez\Ui
 * @author Derek Li
 */
class Table extends Html\Div
{
    /**
     * @var TablePrototype
     */
    protected $tablePrototype;

    /**
     * Table components.
     *
     * @var array
     */
    protected $components = array();

    /**
     * Set table prototype. And add it to $this as a single child.
     *
     * @param mixed $tablePrototype
     * @return $this
     */
    public function setTablePrototype($tablePrototype)
    {
        $this->tablePrototype = $tablePrototype;
        $this->removeChildren();
        $this->add($tablePrototype);
        return $this;
    }

    /**
     * Get the table prototype instance.
     *
     * @return TablePrototype
     */
    public function getTablePrototype()
    {
        if (!isset($this->tablePrototype)) {
            $this->setTablePrototype(new TablePrototype());
        }
        return $this->tablePrototype;
    }

    /**
     * Get all the components.
     *
     * @return array
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * Add table component.
     *
     * @param string $componentName Name of the component.
     * @param mixed $callable The custom function to inject.
     * @return $this
     */
    public function addComponent($componentName, $callable = null)
    {
        if (!array_key_exists($componentName, $this->components)) {
            $componentClass = '\\Ez\\Ui\\Table\\Component\\'.$componentName;
            if (class_exists($componentClass, true)) {
                $component = new $componentClass();
                if ($component instanceof TableComponent\ComponentInterface) {
                    $this->components[$componentName] = $component;
                    if (is_callable($callable)) {
                        $callable($component);
                    }
                    $component->decorate($this->getTablePrototype());
                }
            }
        }
        return $this;
    }
}