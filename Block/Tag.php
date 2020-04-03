<?php
namespace Sga\Tracker\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Registry;
use Magento\Cms\Model\Template\FilterProvider;
use Sga\Tracker\Helper\Data;
use Sga\Tracker\Model\Tag as TagModel;
use Sga\Tracker\Model\ResourceModel\Tag\Collection as TagCollection;

class Tag extends \Magento\Framework\View\Element\Template
{
    protected $_helperData;
    protected $_jsonSerializer;
    protected $_filterProvider;
    protected $_tagCollection;
    protected $_coreRegistry;
    protected $_cmsPage;

    protected $_location;
    protected $_hasSpecificUrl;

    public function __construct(
        Context $context,
        Data $helperData,
        Json $jsonSerializer,
        FilterProvider $filterProvider,
        TagCollection $tagCollection,
        Registry $registry,
        \Magento\Cms\Model\Page $cmsPage
    ) {
        $this->_helperData = $helperData;
        $this->_jsonSerializer = $jsonSerializer;
        $this->_filterProvider = $filterProvider;
        $this->_tagCollection = $tagCollection;
        $this->_coreRegistry = $registry;
        $this->_cmsPage = $cmsPage;

        parent::__construct($context);
    }

    public function getJsonSerializer()
    {
        return $this->_jsonSerializer;
    }

    public function getHelperData()
    {
        return $this->_helperData;
    }

    public function setLocation($location)
    {
        $this->_location = $location;
        $this->_hasSpecificUrl = $this->getHelperData()->locationHasSpecificUrl($location);

        return $this;
    }

    public function getTags()
    {
        $collection = clone $this->_tagCollection;

        $category = $this->_coreRegistry->registry('current_category');
        $product = $this->_coreRegistry->registry('current_product');

        // Add active filter
        $collection->addActiveFilter();

        // add location filter
        $collection->addLocationFilter($this->_location);

        // add store filter
        $collection->addStoreFilter();

        if (!$this->_hasSpecificUrl) {
            $keyAction = $this->_request->getModuleName() . '_' . $this->_request->getControllerName() . '_' . $this->_request->getActionName();

            // add url filter
            $collection->addCurrentUrlFilter();

            // add categories filter
            if ($keyAction === 'catalog_category_view' && $category instanceof \Magento\Catalog\Model\Category) {
                $collection->addCategoryFilter($category);
            }

            // add products categories filter
            if ($keyAction === 'catalog_product_view' && $category instanceof \Magento\Catalog\Model\Category) {
                $collection->addProductCategoryFilter($category);
            }

            // add products skus filter
            if ($keyAction === 'catalog_product_view' && $product instanceof \Magento\Catalog\Model\Product) {
                $collection->addProductSkuFilter($product);
            }

            // add cms page filter
            if ($keyAction === 'cms_index_index' || $keyAction === 'cms_page_view') {
                if ($this->_cmsPage instanceof \Magento\Cms\Model\Page && $this->_cmsPage->getId() > 0) {
                    $this->_tagCollection->addCmsPageFilter($this->_cmsPage);
                }
            }
        }

        // add sort by position
        $collection->addOrder('position', 'ASC');

        $items = $collection->getItems();

        /*if (Mage::getSingleton('sgatracker/config')->isDebug()) {
            Mage::log($this->_tagCollection->getSelect()->assemble(), null, 'sgatracker.log');
        }*/

        return $items;
    }

    public function getBlockTag(TagModel $tag)
    {
        $blockType = $tag->getBlockType() != '' ? $tag->getBlockType() : 'default';
        $blockClass = $this->getHelperData()->getBlockBlockType($blockType);
        return $this->getLayout()->createBlock($blockClass)->setTag($tag);
    }
}