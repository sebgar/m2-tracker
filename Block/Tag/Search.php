<?php
namespace Sga\Tracker\Block\Tag;

class Search extends \Sga\Tracker\Block\Tag\DefaultType
{
    protected function _loadContextVars()
    {
        parent::_loadContextVars();

        $q = $this->_request->getParam('q');
        $this->setSearchQuery($q);
    }
}