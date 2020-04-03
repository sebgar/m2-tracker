<?php
namespace Sga\Tracker\Model\Tag;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Sga\Tracker\Model\ResourceModel\Tag\CollectionFactory;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    protected $collection;
    protected $dataPersistor;
    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $item->getResource()->unpackData($item);
            $this->loadedData[$item->getId()] = $item->getData();
        }

        $data = $this->dataPersistor->get('tracker_tag');
        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);

            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('tracker_tag');
        }

        return $this->loadedData;
    }
}
