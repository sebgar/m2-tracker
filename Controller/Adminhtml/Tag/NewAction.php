<?php
namespace Sga\Tracker\Controller\Adminhtml\Tag;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Sga\Tracker\Controller\Adminhtml\Tag as ParentClass;

class NewAction extends ParentClass implements HttpGetActionInterface
{
    public function execute()
    {
        $resultForward = $this->_resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
