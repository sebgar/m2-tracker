<?php
namespace Sga\Tracker\Controller\Adminhtml;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Ui\Component\MassAction\Filter as MassActionFilter;
use Sga\Tracker\Model\TagFactory as ModelFactory;
use Sga\Tracker\Model\ResourceModel\Tag\CollectionFactory;
use Sga\Tracker\Api\TagRepositoryInterface as ModelRepository;

abstract class Tag extends Action
{
    const ADMIN_RESOURCE = 'Sga_Tracker::tracker_tag';

    protected $_resultPageFactory;
    protected $_resultForwardFactory;
    protected $_jsonFactory;
    protected $_modelFactory;
    protected $_modelRepository;
    protected $_collectionFactory;
    protected $_massActionFilter;
    protected $_dataPersistor;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        JsonFactory $jsonFactory,
        ModelFactory $modelFactory,
        ModelRepository $modelRepository,
        CollectionFactory $collectionFactory,
        MassActionFilter $massActionFilter,
        DataPersistorInterface $dataPersistor
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultForwardFactory = $resultForwardFactory;
        $this->_jsonFactory = $jsonFactory;
        $this->_modelFactory = $modelFactory;
        $this->_modelRepository = $modelRepository;
        $this->_collectionFactory = $collectionFactory;
        $this->_massActionFilter = $massActionFilter;
        $this->_dataPersistor = $dataPersistor;

        parent::__construct($context);
    }

    protected function initPage(Page $resultPage)
    {
        $resultPage->setActiveMenu('Sga_Tracker::tracker_tag')
            ->addBreadcrumb(__('TRACKER'), __('TRACKER'))
            ->addBreadcrumb(__('Tags'), __('Tags'));

        $resultPage->getConfig()->getTitle()->prepend(__('TRACKER Tags'));

        return $resultPage;
    }
}
