<?php
namespace Sga\Tracker\Controller\Adminhtml\Tag;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Sga\Tracker\Controller\Adminhtml\Tag as ParentClass;

class Save extends ParentClass implements HttpPostActionInterface
{
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = 1;
            }
            if (empty($data['tag_id'])) {
                $data['tag_id'] = null;
            }

            $model = $this->_modelFactory->create();

            $id = $this->getRequest()->getParam('tag_id');
            if ($id) {
                try {
                    $model = $this->_modelRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This tag no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->_modelRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the tag.'));
                $this->_dataPersistor->clear('tracker_tag');
                return $this->processReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the tag.'));
            }

            $this->_dataPersistor->set('tracker_tag', $data);
            return $resultRedirect->setPath('*/*/edit', ['tag_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    private function processReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';

        if ($redirect ==='continue') {
            $resultRedirect->setPath('*/*/edit', ['tag_id' => $model->getId()]);
        } else if ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        } else if ($redirect === 'duplicate') {
            $duplicateModel = $this->_modelFactory->create(['data' => $data]);
            $duplicateModel->setId(null);
            $duplicateModel->setIsActive(0);
            $this->_modelRepository->save($duplicateModel);

            $id = $duplicateModel->getId();
            $this->messageManager->addSuccessMessage(__('You duplicated the tag.'));
            $this->_dataPersistor->set('tracker_tag', $data);
            $resultRedirect->setPath('*/*/edit', ['tag_id' => $id]);
        }
        return $resultRedirect;
    }
}
