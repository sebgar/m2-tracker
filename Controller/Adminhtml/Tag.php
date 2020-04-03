<?php
namespace Sga\Tracker\Controller\Adminhtml;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\ForwardFactory;

abstract class Tag extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Sga_Tracker::tracker_tag';

    protected $_resultPageFactory;
    protected $_resultForwardFactory;
    protected $_jsonFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory = null,
        ForwardFactory $resultForwardFactory = null,
        JsonFactory $jsonFactory = null
    ) {
        $this->_resultPageFactory = $resultPageFactory ?: \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\View\Result\PageFactory');
        $this->_resultForwardFactory = $resultForwardFactory ?: \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Backend\Model\View\Result\ForwardFactory');
        $this->_jsonFactory = $jsonFactory ?: \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\Controller\Result\JsonFactory');

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
