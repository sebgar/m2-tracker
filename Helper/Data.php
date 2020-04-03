<?php
namespace Sga\Tracker\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Data extends AbstractHelper
{
    protected $_scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->_scopeConfig = $scopeConfig;
    }

    public function locationHasSpecificUrl($location)
    {
        $node = $this->_scopeConfig->get('system', 'default/sga_tracker/location/'.$location);
        if (is_array($node) && isset($node['url']) && $node['url'] !== '') {
            return true;
        }

        return false;
    }

    public function getBlockBlockType($blocktype)
    {
        $node = $this->_scopeConfig->get('system', 'default/sga_tracker/blocktype/'.$blocktype);
        if (is_array($node) && isset($node['block']) && $node['block'] !== '') {
            return $node['block'];
        }

        return '\Sga\Tracker\Block\Tag\DefaultType';
    }
}