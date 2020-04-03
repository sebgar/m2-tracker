<?php
namespace Sga\Tracker\Model\Source\Tag;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Location implements OptionSourceInterface
{
    protected $_scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->_scopeConfig = $scopeConfig;
    }

    public function getOptions()
    {
        $list = [];

        $nodes = $this->_scopeConfig->get('system', 'default/sga_tracker/location');
        if (is_array($nodes)) {
            foreach ($nodes as $key => $node) {
                $list[$key] = isset($node['label']) ? __($node['label']) : $key;
            }
        }
        return $list;
    }

    public function toOptionArray()
    {
        $availableOptions = $this->getOptions();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }

    public function toArray()
    {
        return $this->getOptions();
    }
}