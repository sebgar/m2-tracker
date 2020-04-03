<?php
namespace Sga\Tracker\Api\Data;

interface TagInterface
{
    const TAG_ID = 'tag_id';
    const IS_ACTIVE = 'is_active';
    const IDENTIFIER = 'identifier';
    const NAME = 'name';
    const POSITION = 'position';
    const LOCATION = 'location';
    const BLOCK_TYPE = 'block_type';
    const CONTENT = 'content';
    const HIDE_NULL_TAGS = 'hide_null_tags';
    const FORMAT_SERIALIZED = 'format_serialized';
    const MAPPING_SERIALIZED = 'mapping_serialized';
    const ALL_URL = 'all_url';
    const URL = 'url';
    const URL_EXCLUDED = 'url_excluded';
    const ALL_CATEGORIES = 'all_categories';
    const CATEGORIES_IDS = 'categories_ids';
    const ALL_PRODUCTS_CAT = 'all_products_cat';
    const PRODUCT_IDS = 'product_ids';
    const SKUS = 'skus';
    const CMS_PAGE_IDS = 'cms_page_ids';
    const ALL_CMS_PAGES = 'all_cms_pages';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get tag id
     *
     * @return int|null
     */
    public function getTagId();

    /**
     * Set tag id
     *
     * @param int $id
     * @return TagInterface
     */
    public function setTagId($id);
    
    /**
     * Get is_active
     *
     * @return bool|null
     */
    public function getIsActive();

    /**
     * Set is_active
     *
     * @param bool $value
     * @return TagInterface
     */
    public function setIsActive($value);
    
    /**
     * Get identifier
     *
     * @return string|null
     */
    public function getIdentifier();

    /**
     * Set identifier
     *
     * @param string $value
     * @return TagInterface
     */
    public function setIdentifier($value);
    
    /**
     * Get name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $value
     * @return TagInterface
     */
    public function setName($value);
    
    /**
     * Get position
     *
     * @return int|null
     */
    public function getPosition();

    /**
     * Set position
     *
     * @param int $value
     * @return TagInterface
     */
    public function setPosition($value);
    
    /**
     * Get location
     *
     * @return string|null
     */
    public function getLocation();

    /**
     * Set location
     *
     * @param string $value
     * @return TagInterface
     */
    public function setLocation($value);
    
    /**
     * Get block_type
     *
     * @return string|null
     */
    public function getBlockType();

    /**
     * Set block_type
     *
     * @param string $value
     * @return TagInterface
     */
    public function setBlockType($value);
    
    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent();

    /**
     * Set content
     *
     * @param string $value
     * @return TagInterface
     */
    public function setContent($value);
    
    /**
     * Get hide_null_tags
     *
     * @return int|null
     */
    public function getHideNullTags();

    /**
     * Set hide_null_tags
     *
     * @param int $value
     * @return TagInterface
     */
    public function setHideNullTags($value);
    
    /**
     * Get format_serialized
     *
     * @return string|null
     */
    public function getFormatSerialized();

    /**
     * Set format_serialized
     *
     * @param string $value
     * @return TagInterface
     */
    public function setFormatSerialized($value);
    
    /**
     * Get mapping_serialized
     *
     * @return string|null
     */
    public function getMappingSerialized();

    /**
     * Set mapping_serialized
     *
     * @param string $value
     * @return TagInterface
     */
    public function setMappingSerialized($value);
    
    /**
     * Get all_url
     *
     * @return int|null
     */
    public function getAllUrl();

    /**
     * Set all_url
     *
     * @param int $value
     * @return TagInterface
     */
    public function setAllUrl($value);
    
    /**
     * Get url
     *
     * @return string|null
     */
    public function getUrl();

    /**
     * Set url
     *
     * @param string $value
     * @return TagInterface
     */
    public function setUrl($value);
    
    /**
     * Get url_excluded
     *
     * @return string|null
     */
    public function getUrlExcluded();

    /**
     * Set url_excluded
     *
     * @param string $value
     * @return TagInterface
     */
    public function setUrlExcluded($value);
    
    /**
     * Get all_categories
     *
     * @return bool|null
     */
    public function getAllCategories();

    /**
     * Set all_categories
     *
     * @param bool $value
     * @return TagInterface
     */
    public function setAllCategories($value);
    
    /**
     * Get categories_ids
     *
     * @return string|null
     */
    public function getCategoriesIds();

    /**
     * Set categories_ids
     *
     * @param string $value
     * @return TagInterface
     */
    public function setCategoriesIds($value);
    
    /**
     * Get all_products_cat
     *
     * @return bool|null
     */
    public function getAllProductsCat();

    /**
     * Set all_products_cat
     *
     * @param bool $value
     * @return TagInterface
     */
    public function setAllProductsCat($value);
    
    /**
     * Get product_ids
     *
     * @return string|null
     */
    public function getProductIds();

    /**
     * Set product_ids
     *
     * @param string $value
     * @return TagInterface
     */
    public function setProductIds($value);
    
    /**
     * Get skus
     *
     * @return string|null
     */
    public function getSkus();

    /**
     * Set skus
     *
     * @param string $value
     * @return TagInterface
     */
    public function setSkus($value);
    
    /**
     * Get cms_page_ids
     *
     * @return string|null
     */
    public function getCmsPageIds();

    /**
     * Set cms_page_ids
     *
     * @param string $value
     * @return TagInterface
     */
    public function setCmsPageIds($value);
    
    /**
     * Get all_cms_pages
     *
     * @return bool|null
     */
    public function getAllCmsPages();

    /**
     * Set all_cms_pages
     *
     * @param bool $value
     * @return TagInterface
     */
    public function setAllCmsPages($value);
    
    /**
     * Get created_at
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     *
     * @param string $value
     * @return TagInterface
     */
    public function setCreatedAt($value);
    
    /**
     * Get updated_at
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     *
     * @param string $value
     * @return TagInterface
     */
    public function setUpdatedAt($value);
    
}
