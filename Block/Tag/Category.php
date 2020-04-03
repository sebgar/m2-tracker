<?php
namespace Sga\Tracker\Block\Tag;

class Category extends \Sga\Tracker\Block\Tag\DefaultType
{
    protected function _loadContextVars()
    {
        parent::_loadContextVars();

        $this->setCategory($this->_registry->registry('current_category'));

        /*if ($this->_hasTagCategoryParent() || $this->_hasTagCategoryLevel()) {
            $pathIds = explode('/', $category->getPath());
            array_shift($pathIds); // remove root
            array_shift($pathIds); // remove level 1

            if (count($pathIds) > 0) {
                $category->setData('level' . ($category->getLevel() - 1), $category);

                if (count($pathIds) > 1) {
                    array_pop($pathIds); // remove current

                    $categories = Mage::getResourceModel('catalog/category_collection')
                        ->addAttributeToSelect('*')
                        ->addFieldToFilter('entity_id', array('in' => $pathIds));

                    // add category by level
                    foreach ($pathIds as $pathId) {
                        $cat = $categories->getItemById($pathId);
                        $category->setData('level' . ($cat->getLevel() - 1), $cat);
                    }

                    // add category by parent
                    $pathIdsReverse = array_reverse($pathIds);
                    $objectName = 'parent';
                    foreach ($pathIdsReverse as $pathId) {
                        $cat = $categories->getItemById($pathId);
                        $category->setData($objectName, $cat);
                        $objectName .= '_parent';
                    }
                }
            }
        }

        // add product skus displayed and total price
        $productSkus = array();
        $productPrices = 0;
        $collection = Mage::getSingleton('catalog/layer')->getProductCollection();
        if ($collection instanceof Mage_Catalog_Model_Resource_Product_Collection) {
            if (!$collection->isLoaded()) {
                $collection = clone $collection;
                $toolbar = Mage::getBlockSingleton('catalog/product_list_toolbar');
                $collection->setCurPage($toolbar->getCurrentPage());
                $collection->setPageSize($toolbar->getLimit());
            }

            foreach ($collection as $item) {
                $productSkus[] = $item->getSku();
                $productPrices += $item->getFinalPrice();
            }
        }
        $this->setProductSkusDisplayed('"'.implode('","', $productSkus).'"');
        $this->setProductPricesDisplayed($productPrices);
        $this->setCurrentProductCollection($collection);*/
    }

    protected function _hasTagCategoryParent()
    {
        return preg_match('#\{\{var\s+category\.parent\.#', $this->getTag()->getContent()) > 0 ? true : false;
    }

    protected function _hasTagCategoryLevel()
    {
        return preg_match('#\{\{var\s+category\.level#', $this->getTag()->getContent()) > 0 ? true : false;
    }
}