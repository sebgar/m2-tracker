<?php
namespace Sga\Tracker\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Sga\Tracker\Api\Data\TagInterface as ModelInterface;
use Sga\Tracker\Model\ResourceModel\Tag as ResourceModel;

class Tag extends AbstractModel implements IdentityInterface, ModelInterface
{
    const CACHE_TAG = 'tracker_tag';

    protected $_eventPrefix = 'tracker_tag';

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getTagId()
    {
        return $this->getData(self::TAG_ID);
    }

    public function setTagId($id)
    {
        return $this->setData(self::TAG_ID, $id);
    }

    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    public function setIsActive($value)
    {
        return $this->setData(self::IS_ACTIVE, $value);
    }

    public function getIdentifier()
    {
        return $this->getData(self::IDENTIFIER);
    }

    public function setIdentifier($value)
    {
        return $this->setData(self::IDENTIFIER, $value);
    }

    public function getName()
    {
        return $this->getData(self::NAME);
    }

    public function setName($value)
    {
        return $this->setData(self::NAME, $value);
    }

    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }

    public function setPosition($value)
    {
        return $this->setData(self::POSITION, $value);
    }

    public function getLocation()
    {
        return $this->getData(self::LOCATION);
    }

    public function setLocation($value)
    {
        return $this->setData(self::LOCATION, $value);
    }

    public function getBlockType()
    {
        return $this->getData(self::BLOCK_TYPE);
    }

    public function setBlockType($value)
    {
        return $this->setData(self::BLOCK_TYPE, $value);
    }

    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    public function setContent($value)
    {
        return $this->setData(self::CONTENT, $value);
    }

    public function getHideNullTags()
    {
        return $this->getData(self::HIDE_NULL_TAGS);
    }

    public function setHideNullTags($value)
    {
        return $this->setData(self::HIDE_NULL_TAGS, $value);
    }

    public function getFormatSerialized()
    {
        return $this->getData(self::FORMAT_SERIALIZED);
    }

    public function setFormatSerialized($value)
    {
        return $this->setData(self::FORMAT_SERIALIZED, $value);
    }

    public function getMappingSerialized()
    {
        return $this->getData(self::MAPPING_SERIALIZED);
    }

    public function setMappingSerialized($value)
    {
        return $this->setData(self::MAPPING_SERIALIZED, $value);
    }

    public function getAllUrl()
    {
        return $this->getData(self::ALL_URL);
    }

    public function setAllUrl($value)
    {
        return $this->setData(self::ALL_URL, $value);
    }

    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    public function setUrl($value)
    {
        return $this->setData(self::URL, $value);
    }

    public function getUrlExcluded()
    {
        return $this->getData(self::URL_EXCLUDED);
    }

    public function setUrlExcluded($value)
    {
        return $this->setData(self::URL_EXCLUDED, $value);
    }

    public function getAllCategories()
    {
        return $this->getData(self::ALL_CATEGORIES);
    }

    public function setAllCategories($value)
    {
        return $this->setData(self::ALL_CATEGORIES, $value);
    }

    public function getCategoriesIds()
    {
        return $this->getData(self::CATEGORIES_IDS);
    }

    public function setCategoriesIds($value)
    {
        return $this->setData(self::CATEGORIES_IDS, $value);
    }

    public function getAllProductsCat()
    {
        return $this->getData(self::ALL_PRODUCTS_CAT);
    }

    public function setAllProductsCat($value)
    {
        return $this->setData(self::ALL_PRODUCTS_CAT, $value);
    }

    public function getProductIds()
    {
        return $this->getData(self::PRODUCT_IDS);
    }

    public function setProductIds($value)
    {
        return $this->setData(self::PRODUCT_IDS, $value);
    }

    public function getSkus()
    {
        return $this->getData(self::SKUS);
    }

    public function setSkus($value)
    {
        return $this->setData(self::SKUS, $value);
    }

    public function getCmsPageIds()
    {
        return $this->getData(self::CMS_PAGE_IDS);
    }

    public function setCmsPageIds($value)
    {
        return $this->setData(self::CMS_PAGE_IDS, $value);
    }

    public function getAllCmsPages()
    {
        return $this->getData(self::ALL_CMS_PAGES);
    }

    public function setAllCmsPages($value)
    {
        return $this->setData(self::ALL_CMS_PAGES, $value);
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt($value)
    {
        return $this->setData(self::CREATED_AT, $value);
    }

    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    public function setUpdatedAt($value)
    {
        return $this->setData(self::UPDATED_AT, $value);
    }

    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }
}
