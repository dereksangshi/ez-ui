<?php

namespace Ez\Ui\Table\Component;

use Ez\Ui\Table\Prototype as TablePrototype;
use Ez\Html\Div;

/**
 * Class UrlBuilding
 * @package Ez\Ui\Table\Component
 * @author Derek Li
 */
class UrlBuilding implements ComponentInterface
{
    /**
     * @var string
     */
    protected $uri = null;

    /**
     * @var array
     */
    protected $urlParams = array();

    /**
     * Set uri.
     *
     * @param string $uri
     * @return $this
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Get uri.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set url parameters.
     *
     * @param array $urlParams
     * @return $this
     */
    public function setUrlParams(array $urlParams)
    {
        $this->urlParams = $urlParams;
        return $this;
    }

    /**
     * Get url parameters.
     *
     * @return array
     */
    public function getUrlPrarms()
    {
        return $this->urlParams;
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
        $tablePrototype->addDecorationToBottomToolbar(function (Div $bottomToolbar) use ($self) {
            $urlBuildingContainer = new Div();
            $urlBuildingContainer->attr(array('class'=>'BIT-E-URL', 'style'=>'display:none'));
            $uriContainer = new Div();
            $uriContainer->attr(array('class'=>'uri'));
            $uriContainer->add($this->getUri());
            $urlBuildingContainer->add($uriContainer);
            $urlParamsContainer = new Div();
            $urlParamsContainer->attr(array('class'=>'params'));
            $urlParamsContainer->add(json_encode($this->getUrlPrarms()));
            $urlBuildingContainer->add($urlParamsContainer);
            $bottomToolbar->add($urlBuildingContainer);
        });
        return $this;
    }
}