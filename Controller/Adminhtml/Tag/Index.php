<?php
namespace Sga\Tracker\Controller\Adminhtml\Tag;

use Sga\Tracker\Controller\Adminhtml\Tag as ParentClass;

class Index extends ParentClass
{
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $this->initPage($resultPage);

        return $resultPage;
    }
}
