<?php
namespace Sga\Tracker\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface TagSearchResultsInterface extends SearchResultsInterface
{
    public function getItems();

    public function setItems(array $items);
}
