<?php
namespace Sga\Tracker\Block\Tag;

use Magento\Framework\View\Element\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Sales\Model\OrderFactory;

class Order extends \Sga\Tracker\Block\Tag\Checkout
{
    protected $_orderFactory;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        CustomerSession $customerSession,
        Registry $registry,
        CheckoutSession $checkoutSession,
        FilterManager $filterManager,
        OrderFactory $orderFactory,
        array $data = []
    ){
        $this->_orderFactory = $orderFactory;

        return parent::__construct($context, $storeManager, $scopeConfig, $customerSession, $registry, $checkoutSession, $filterManager, $data);
    }

    protected function _loadContextVars()
    {
        parent::_loadContextVars();

        $orderId = (int)$this->_checkoutSession->getLastOrderId();
        if ($orderId > 0) {
            $order = $this->_orderFactory->create()->load($orderId);
            if ((int)$order->getId() > 0) {
                $this->setOrder($order);

                $specific = $this->getSpecific();

                $itemsSku = array();
                $itemsName = array();
                $itemsPrice = array();
                $itemsPriceHt = array();
                $productsId = array();
                $orderQuantities = 0;
                $orderCost = 0;
                foreach ($order->getAllVisibleItems() as $item) {
                    $itemsSku[] = $item->getSku();
                    $itemsName[] = $this->_filterManager->removeAccents($item->getName());
                    $itemsPrice[] = round($item->getRowTotalInclTax(), 2);
                    $itemsPriceHt[] = round($item->getRowTotal(), 2);
                    $productsId[] = $item->getProductId();
                    $orderQuantities += $item->getQtyOrdered();
                    $orderCost += $item->getRowTotalInclTax();
                }
                $specific->setOrderItemsSku(implode(',', $itemsSku));
                $specific->setOrderItemsSkuWithQuote('"'.implode('","', $itemsSku).'"');
                $specific->setOrderItemsName(implode('#', $itemsName));
                $specific->setOrderItemsPrice(implode(',', $itemsPrice));
                $specific->setOrderItemsPriceHt(implode(',', $itemsPriceHt));
                $specific->setOrderProductsId(implode(',', $productsId));
                $specific->setOrderQuantities($orderQuantities);
                $specific->setOrderCostTtc(round($orderCost, 2));
                $specific->setOrderGrandTotalWithoutTaxAmount(round($order->getGrandTotal()-$order->getTaxAmount(), 2));

                $nbOrders = $this->_orderFactory->create()->getCollection()
                    ->addFieldToFilter('customer_id', $order->getCustomerId())
                    ->getSize();
                $specific->setCustomerNbOrders($nbOrders);
            }
        }
    }
}