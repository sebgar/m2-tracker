<?php
namespace Sga\Tracker\Controller\Adminhtml\Tag;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Sga\Tracker\Model\Tag as Model;
use Sga\Tracker\Controller\Adminhtml\Tag as ParentClass;

class Edit extends ParentClass implements HttpGetActionInterface
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('tag_id');
        $model = $this->_objectManager->create(Model::class);

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This tag no longer exists.'));

                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $resultPage = $this->_resultPageFactory->create();
        $this->initPage($resultPage)
            ->addBreadcrumb(
            $id ? __('Edit Tag') : __('New Tag'),
            $id ? __('Edit Tag') : __('New Tag')
            );
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? 'Tag #'.$model->getId() : __('New Tag'));

        return $resultPage;
    }
}
