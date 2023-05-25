<?php

namespace Mageplaza\GiftCard\Ui\Component\Listing\Column;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Directory\Model\Currency;

class Price extends Column
{
    protected $priceFormatter;
    private $currency;
    private $storeManager;

    public function __construct(ContextInterface       $context,
                                UiComponentFactory     $uiComponentFactory,
                                PriceCurrencyInterface $priceFormatter,
                                array                  $components = [],
                                array                  $data = [],
                                Currency               $currency = null,
                                StoreManagerInterface  $storeManager = null)
    {
        $this->priceFormatter = $priceFormatter;
        $this->currency = $currency ?: ObjectManager::getInstance()
            ->get(Currency::class);
        $this->storeManager = $storeManager ?: ObjectManager::getInstance()
            ->get(StoreManagerInterface::class);
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $currencyCode = isset($item['base_currency_code']) ? $item['base_currency_code'] : null;
                if (!$currencyCode) {
                    $storeId = isset($item['store_id']) && (int)$item['store_id'] !== 0 ? $item['store_id'] :
                        $this->context->getFilterParam('store_id', Store::DEFAULT_STORE_ID);
                    $store = $this->storeManager->getStore(
                        $storeId
                    );
                    $currencyCode = $store->getBaseCurrency()->getCurrencyCode();
                }
                $basePurchaseCurrency = $this->currency->load($currencyCode);
                $item[$this->getData('name')] = $basePurchaseCurrency
                    ->format($item[$this->getData('name')], [], false);
            }
        }
        return $dataSource;
    }
}
