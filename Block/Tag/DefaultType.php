<?php
namespace Sga\Tracker\Block\Tag;

use Magento\Framework\View\Element\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Registry;

class DefaultType extends \Sga\Tracker\Block\Tag\AbstractType
{
    protected $_storeManager;
    protected $_scopeConfig;
    protected $_customerSession;
    protected $_registry;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        CustomerSession $customerSession,
        Registry $registry,
        array $data = []
    ){
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
        $this->_customerSession = $customerSession;
        $this->_registry = $registry;

        return parent::__construct($context, $data);
    }

    protected function _loadContextVars()
    {
        if ($this->_request->isSecure()) {
            $url = 'https://';
        } else {
            $url = 'http://';
        }

        $this->setCurrentUrl($url . $this->_request->getServer('HTTP_HOST') . $this->_request->getServer('REQUEST_URI'));
        $this->setRefererUrl($this->_request->getServer('HTTP_REFERER'));
        $this->setStoreCurrentCurrencyCode($this->_storeManager->getStore()->getCurrentCurrencyCode());
        $this->setStoreCurrentCountry($this->_scopeConfig->getValue(
            \Magento\Directory\Helper\Data::XML_PATH_DEFAULT_COUNTRY,
            ScopeInterface::SCOPE_STORE
        ));

        $locale = $this->_scopeConfig->getValue(
            \Magento\Directory\Helper\Data::XML_PATH_DEFAULT_LOCALE,
            ScopeInterface::SCOPE_STORE
        );
        $this->setStoreCurrentLocale($locale);
        $parts = explode('_', $locale);
        $this->setStoreCurrentLang(isset($parts[0]) ? strtoupper($parts[0]) : '');

        $this->_loadCustomerVars();
    }

    protected function _loadCustomerVars()
    {
        $customer = $this->_customerSession->getCustomer();
        if ($customer instanceof \Magento\Customer\Model\Customer && (int)$customer->getId() > 0) {
            $this->setCustomer($customer);
        }

        $this->setCustomerIsLogged((int)$this->_customerSession->isLoggedIn());
    }
}