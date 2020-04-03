<?php
namespace Sga\Tracker\Block\Adminhtml\Tag\Edit;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Backend\Block\Widget\Context;
use Sga\Tracker\Api\TagRepositoryInterface as RepositoryInterface;

class GenericButton
{
    protected $context;
    protected $repository;

    /**
     * @param Context $context
     * @param RepositoryInterface $repository
     */
    public function __construct(
        Context $context,
        RepositoryInterface $repository
    ) {
        $this->context = $context;
        $this->repository = $repository;
    }

    /**
     * Return model id
     *
     * @return |null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getId()
    {
        try {
            return $this->repository->getById(
                $this->context->getRequest()->getParam('tag_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
