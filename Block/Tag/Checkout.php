<?php
namespace Sga\Tracker\Block\Tag;

use Magento\Framework\View\Element\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Registry;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Filter\FilterManager;

class Checkout extends \Sga\Tracker\Block\Tag\DefaultType
{
    protected $_checkoutSession;
    protected $_filterManager;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        CustomerSession $customerSession,
        Registry $registry,
        CheckoutSession $checkoutSession,
        FilterManager $filterManager,
        array $data = []
    ){
        $this->_checkoutSession = $checkoutSession;
        $this->_filterManager = $filterManager;

        return parent::__construct($context, $storeManager, $scopeConfig, $customerSession, $registry, $data);
    }

    protected function _loadContextVars()
    {
        parent::_loadContextVars();

        $quote = $this->_checkoutSession->getQuote();
        $this->setQuote($quote);

        $specific = new \Magento\Framework\DataObject();
        $itemsSku = array();
        $itemsName = array();
        $itemsPrice = array();
        $itemsPriceHt = array();
        $productsId = array();
        $cartQuantities = 0;
        $cartCostTtc = 0;
        foreach ($quote->getAllVisibleItems() as $item) {
            $itemsSku[] = $item->getSku();
            $itemsName[] = $this->_filterManager->removeAccents($item->getName());
            $itemsPrice[] = round($item->getRowTotalInclTax(), 2);
            $itemsPriceHt[] = round($item->getRowTotal(), 2);
            $productsId[] = $item->getProductId();
            $cartQuantities += $item->getQty();
            $cartCostTtc += $item->getRowTotalInclTax();
        }
        $specific->setCartItemsSku(implode(',', $itemsSku));
        $specific->setCartItemsSkuWithQuote('"'.implode('","', $itemsSku).'"');
        $specific->setCartItemsName(implode('#', $itemsName));
        $specific->setCartItemsPrice(implode(',', $itemsPrice));
        $specific->setCartItemsPriceHt(implode(',', $itemsPriceHt));
        $specific->setCartProductsId(implode(',', $productsId));
        $specific->setCartQuantities($cartQuantities);
        $specific->setCartCostTtc(round($cartCostTtc, 2));

        $this->setSpecific($specific);
    }
}