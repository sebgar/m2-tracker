<?php
namespace Sga\Tracker\Api;

use Sga\Tracker\Api\Data\TagInterface as ModelInterface;

interface TagRepositoryInterface
{
    public function save(ModelInterface $model);

    public function getById($id);

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    public function delete(ModelInterface $model);

    public function deleteById($id);
}
