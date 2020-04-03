<?php
namespace Sga\Tracker\Model\ResourceModel;

use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\StoreManagerInterface;
use Sga\Tracker\Api\Data\TagInterface as ModelInterface;

class Tag extends AbstractDb
{
    protected $_storeManager;
    protected $_entityManager;
    protected $_metadataPool;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        $connectionName = null
    ) {
        $this->_storeManager = $storeManager;
        $this->_entityManager = $entityManager;
        $this->_metadataPool = $metadataPool;

        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init('sga_tracker_tag','tag_id');
    }

    public function load(AbstractModel $object, $value, $field = null)
    {
        $this->_entityManager->load($object, (int)$value);
        $this->unpackData($object);
        return $this;
    }

    public function unpackData(AbstractModel $object)
    {
        if (is_string($object->getCategoriesIds())) {
            $object->setCategoriesIds(explode(',', $object->getCategoriesIds()));
        }
        if (is_string($object->getProductIds())) {
            $object->setProductIds(explode(',', $object->getProductIds()));
        }
        if (is_string($object->getCmsPageIds())) {
            $object->setCmsPageIds(explode(',', $object->getCmsPageIds()));
        }

        // convert url
        $urls = explode(',', $object->getUrl());
        $tmp = array();
        foreach ($urls as $url) {
            $tmp[] = trim($url);
        }
        $object->setUrl(implode("\n", $tmp));

        // convert url excluded
        $urls = explode(',', $object->getUrlExcluded());
        $tmp = array();
        foreach ($urls as $url) {
            $tmp[] = trim($url);
        }
        $object->setUrlExcluded(implode("\n", $tmp));

        // convert skus
        $skus = explode(',', $object->getSkus());
        $tmp = array();
        foreach ($skus as $sku) {
            $tmp[] = trim($sku);
        }
        $object->setSkus(implode("\n", $tmp));
    }

    public function save(AbstractModel $object)
    {
        $this->packData($object);
        $this->_entityManager->save($object);
        return $this;
    }

    public function packData(AbstractModel $object)
    {
        if (is_array($object->getCategoriesIds())) {
            $object->setCategoriesIds(implode(',', $object->getCategoriesIds()));
        }
        if (is_array($object->getProductIds())) {
            $object->setProductIds(implode(',', $object->getProductIds()));
        }
        if (is_array($object->getCmsPageIds())) {
            $object->setCmsPageIds(implode(',', $object->getCmsPageIds()));
        }

        // convert urls
        $urls = explode("\n", $object->getUrl());
        $tmp = array();
        foreach ($urls as $url) {
            $url = trim($url);
            if ($url !== '') {
                $tmp[] = trim($url);
            }
        }
        $object->setUrl(implode(',', $tmp));

        // convert urls excluded
        $urls = explode("\n", $object->getUrlExcluded());
        $tmp = array();
        foreach ($urls as $url) {
            $url = trim($url);
            if ($url !== '') {
                $tmp[] = trim($url);
            }
        }
        $object->setUrlExcluded(implode(',', $tmp));

        // convert skus
        $skus = explode("\n", $object->getSkus());
        $tmp = array();
        foreach ($skus as $sku) {
            $sku = trim($sku);
            if ($sku !== '') {
                $tmp[] = trim($sku);
            }
        }
        $object->setSkus(implode(',', $tmp));
    }

    public function delete(AbstractModel $object)
    {
        $this->_entityManager->delete($object);

        return $this;
    }

    public function lookupStoreIds($id)
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->_metadataPool->getMetadata(ModelInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['relation' => $this->getTable('sga_tracker_tag_store')], 'store_id')
            ->join(
                ['main_table' => $this->getMainTable()],
                'relation.' . $linkField . ' = main_table.' . $linkField,
                []
            )
            ->where('relation.' . $entityMetadata->getIdentifierField() . ' = :tag_id');

        return $connection->fetchCol($select, ['tag_id' => (int)$id]);
    }

}
