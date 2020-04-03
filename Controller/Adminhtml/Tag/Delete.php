<?php
namespace Sga\Tracker\Controller\Adminhtml\Tag;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Sga\Tracker\Controller\Adminhtml\Tag as ParentClass;
use Sga\Tracker\Model\Tag as Model;

class Delete extends ParentClass implements HttpPostActionInterface
{
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = $this->getRequest()->getParam('tag_id');
        if ($id) {
            try {
                $model = $this->_objectManager->create(Model::class);
                $model->load($id);
                $model->delete();

                $this->messageManager->addSuccessMessage(__('You deleted the tag.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['tag_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a tag to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
