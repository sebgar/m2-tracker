<?php
namespace Sga\Tracker\Model\ResourceModel\Tag;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\Store;
use Sga\Tracker\Api\Data\TagInterface as ModelInterface;
use Sga\Tracker\Model\Tag as Model;
use Sga\Tracker\Model\ResourceModel\Tag as ResourceModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'tag_id';

    protected $_partsWhere = array(
        'required' => array(),
        'other' => array()
    );

    protected $storeManager;
    protected $metadataPool;
    protected $_request;
    protected $_urlBuilder;

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\EntityManager\MetadataPool $metadataPool,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\UrlInterface $urlBuilder
    ) {
        $this->storeManager = $storeManager;
        $this->metadataPool = $metadataPool;
        $this->_request = $request;
        $this->_urlBuilder = $urlBuilder;

        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);

        $this->_map['fields']['tag_id'] = 'main_table.tag_id';
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    protected function _beforeLoad()
    {
        $wheres = array();

        // Add required where part
        if (is_array($this->_partsWhere['required']) && count($this->_partsWhere['required']) > 0) {
            $wheres = $this->_partsWhere['required'];
        }

        // Add other where part
        if (is_array($this->_partsWhere['other']) && count($this->_partsWhere['other']) > 0) {
            $wheres[] = '(' . implode(' OR ', $this->_partsWhere['other']) . ')';
        }

        if (count($wheres) > 0) {
            $this->getSelect()->where(new \Zend_Db_Expr(implode(' AND ', $wheres)));
        }

        return parent::_beforeLoad();
    }

    public function addStoreFilter($store = null, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $store = $this->storeManager->getStore($store);
            $this->_performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }

    protected function _afterLoad()
    {
        $entityMetadata = $this->metadataPool->getMetadata(ModelInterface::class);
        $this->_performAfterLoad('sga_tracker_tag_store', $entityMetadata->getLinkField());

        return parent::_afterLoad();
    }

    protected function _renderFiltersBefore()
    {
        $entityMetadata = $this->metadataPool->getMetadata(ModelInterface::class);
        $this->_joinStoreRelationTable('sga_tracker_tag_store', $entityMetadata->getLinkField());
    }

    protected function _performAfterLoad($tableName, $linkField)
    {
        $linkedIds = $this->getColumnValues($linkField);
        if (count($linkedIds)) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(['table_store' => $this->getTable($tableName)])
                ->where('table_store.' . $linkField . ' IN (?)', $linkedIds);
            $result = $connection->fetchAll($select);
            if ($result) {
                $storesData = [];
                foreach ($result as $storeData) {
                    $storesData[$storeData[$linkField]][] = $storeData['store_id'];
                }

                foreach ($this as $item) {
                    $linkedId = $item->getData($linkField);
                    if (!isset($storesData[$linkedId])) {
                        continue;
                    }
                    $storeIdKey = array_search(Store::DEFAULT_STORE_ID, $storesData[$linkedId], true);
                    if ($storeIdKey !== false) {
                        $stores = $this->storeManager->getStores(false, true);
                        $storeId = current($stores)->getId();
                        $storeCode = key($stores);
                    } else {
                        $storeId = current($storesData[$linkedId]);
                        $storeCode = $this->storeManager->getStore($storeId)->getCode();
                    }
                    $item->setData('_first_store_id', $storeId);
                    $item->setData('store_code', $storeCode);
                    $item->setData('store_id', $storesData[$linkedId]);
                }
            }
        }
    }

    protected function _performAddStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof Store) {
            $store = [$store->getId()];
        }

        if (!is_array($store)) {
            $store = [$store];
        }

        if ($withAdmin) {
            $store[] = Store::DEFAULT_STORE_ID;
        }

        $this->addFilter('store', ['in' => $store], 'public');
    }

    protected function _joinStoreRelationTable($tableName, $linkField)
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                ['store_table' => $this->getTable($tableName)],
                'main_table.' . $linkField . ' = store_table.' . $linkField,
                []
            )->group(
                'main_table.' . $linkField
            );
        }
    }

    public function addActiveFilter()
    {
        $this->_partsWhere['required'][] = 'is_active=1';
        return $this;
    }

    public function addLocationFilter($location)
    {
        $adapter = $this->getConnection();
        $this->_partsWhere['required'][] = $adapter->quoteInto('location=?', $location);
        return $this;
    }

    public function addCmsPageFilter($page)
    {
        $adapter = $this->getConnection();
        $this->_partsWhere['other'][] = $adapter->quoteInto('(find_in_set(?, cms_page_ids) OR all_cms_pages=1)', $page->getIdentifier());
        return $this;
    }

    public function addCategoryFilter($category)
    {
        $adapter = $this->getConnection();
        $this->_partsWhere['other'][] = $adapter->quoteInto('(find_in_set(?, categories_ids) OR all_categories=1)', (int)$category->getId());
        return $this;
    }

    public function addProductCategoryFilter($category)
    {
        $adapter = $this->getConnection();
        $this->_partsWhere['other'][] = $adapter->quoteInto('(find_in_set(?, product_ids) OR all_products_cat=1)', (int)$category->getId());
        return $this;
    }

    public function addProductSkuFilter($product)
    {
        $adapter = $this->getConnection();
        $this->_partsWhere['other'][] = $adapter->quoteInto('find_in_set(?, skus)', $product->getSku());
        return $this;
    }

    public function addCurrentUrlFilter()
    {
        $adapter = $this->getConnection();
        $listStr = $this->_prepareUrls();

        ///// add included
        $this->_partsWhere['other'][] = 'all_url=1';
        foreach ($listStr as $str) {
            $this->_partsWhere['other'][] = $adapter->quoteInto('FIND_IN_SET(?, url)', $str);
        }

        ///// add excluded
        $excluded = array();
        foreach ($listStr as $str) {
            $excluded[] = $adapter->quoteInto('NOT FIND_IN_SET(?, url_excluded)', $str);
        }
        if (count($excluded) > 0) {
            $this->_partsWhere['required'][] = '(url_excluded = "" OR url_excluded IS NULL OR ('.implode(' AND ', $excluded).'))';
        }

        return $this;
    }

    protected function _prepareUrls()
    {
        $homeUrl = $this->_urlBuilder->getUrl('', array('_nosid' => true));
        $actualUrl = rtrim($homeUrl, '/').$this->_request->getRequestUri();

        if ($homeUrl == $actualUrl) {
            $str = 'home';
        } else {
            $str = substr($actualUrl, (strlen($homeUrl) - 1));
            $str = str_replace('?', '#', $str);
        }

        $listStr = array();
        $listStr[] = $str;

        // url decoded
        if ($str !== urldecode($str)) {
            $listStr[] = urldecode($str);
        }

        // split url by "/"
        $parts = explode('/', trim($str, '/'));
        $count = count($parts);
        for ($i = $count; $i > 1; $i--) {
            array_pop($parts);

            $listStr[] = '/'.implode('/', $parts).'/*';
        }

        // urls with/without action
        $listStr[] = '/' . $this->_request->getModuleName() . '/' . $this->_request->getControllerName();
        $listStr[] = '/' . $this->_request->getModuleName() . '/' . $this->_request->getControllerName() . '/';
        $listStr[] = '/' . $this->_request->getModuleName() . '/' . $this->_request->getControllerName() . '/' . $this->_request->getActionName();
        $listStr[] = '/' . $this->_request->getModuleName() . '/' . $this->_request->getControllerName() . '/' . $this->_request->getActionName() . '/';

        if (preg_match('/\/$/', $str)) {
            $str = substr($str, 0, (strlen($str) - 1));
            $listStr[] = $str;
        }

        return $listStr;
    }
}