<?php

namespace Mageplaza\GiftCard\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Mageplaza\GiftCard\Model\GiftCardFactory;

/**
 * Class Post
 * @package Mageplaza\Blog\Controller\Adminhtml
 */
abstract class Post extends Action
{
    /**
     * Post Factory
     *
     * @var GiftCardFactory
     */
    public $_giftCardFactory;

    /**
     * Core registry
     *
     * @var Registry
     */
    public $coreRegistry;

    /**
     * Post constructor.
     *
     * @param GiftCardFactory $giftCardFactory
     * @param Registry $coreRegistry
     * @param Context $context
     */
    public function __construct(
        GiftCardFactory $giftCardFactory,
        Registry        $coreRegistry,
        Context         $context
    )
    {
        $this->_giftCardFactory = $giftCardFactory;
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * @param bool $register
     * @param bool $isSave
     * @return bool|\Mageplaza\GiftCard\Model\GiftCard\
     */
    protected function initPost($isSave = false)
    {
        $giftCardId = (int)$this->getRequest()->getParam('id');
        /**
         * @var \Mageplaza\GiftCard\Model\GiftCard $giftCard
         */
        $giftCard = $this->_giftCardFactory->create();
        if ($giftCardId) {
            if (!$isSave) {
                $giftCard->load($giftCardId);
                if (!$giftCard->getId()) {
                    $this->messageManager->addErrorMessage(__('This Gift Card no longer exists.'));
                    return false;
                }
            }
        }
        return $giftCard;
    }
}
