<?php
namespace Sga\Tracker\Block\Tag;

class Product extends \Sga\Tracker\Block\Tag\DefaultType
{
    protected function _loadContextVars()
    {
        parent::_loadContextVars();

        $this->setProduct($this->_registry->registry('current_product'));
    }
}