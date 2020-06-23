<?php
namespace Sga\Tracker\Controller\Adminhtml\Tag;

use Magento\Framework\Controller\ResultFactory;
use Sga\Tracker\Controller\Adminhtml\Tag as ParentClass;

class MassEnable extends ParentClass
{
    public function execute()
    {
        $collection = $this->_massActionFilter->getCollection($this->_collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $item) {
            $item->setIsActive(true)->save();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been enabled.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
