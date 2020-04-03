<?php
namespace Sga\Tracker\Controller\Adminhtml\Tag;

use Magento\Backend\App\Action\Context;
use Sga\Tracker\Api\TagRepositoryInterface as ModelRepository;
use Sga\Tracker\Controller\Adminhtml\Tag as ParentClass;

class InlineEdit extends ParentClass
{
    protected $_modelRepository;

    public function __construct(
        Context $context,
        ModelRepository $modelRepository
    ) {
        $this->_modelRepository = $modelRepository;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->_jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $id) {
                    $model = $this->_modelRepository->getById($id);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$id]));
                        $this->_modelRepository->save($model);
                    } catch (\Exception $e) {
                        $messages[] = '[ID: ' . $model->getId() . '] ' . __($e->getMessage());
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
