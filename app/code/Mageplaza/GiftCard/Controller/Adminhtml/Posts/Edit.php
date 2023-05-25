<?php

namespace Mageplaza\GiftCard\Controller\Adminhtml\Posts;

use Magento\Backend\App\Action;

class Edit extends Action
{
    const ADMIN_RESOURCE = 'Mageplaza_GiftCard::posts_edit';

    protected $_resultPageFactory;
    protected $_GiftCardFactory;
    protected $_giftRegistry;

    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\App\Action\Context        $context,
        \Mageplaza\GiftCard\Model\GiftCardFactory  $GiftCardFactory,
        \Magento\Framework\Registry                $registry
    )
    {
        $this->_giftRegistry = $registry;
        $this->_GiftCardFactory = $GiftCardFactory;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        if (!$this->_isAllowed()) {
            $this->messageManager->addErrorMessage(__('You don\'t have permission to access this page.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
        $id = $this->getRequest()->getParam('id');
        $giftcard = $this->_GiftCardFactory->create();
        $giftcard->load($id);
        $this->_giftRegistry->register('giftcard', $giftcard);
        $_resultPage = $this->_resultPageFactory->create();
        if ($id) {
            $_resultPage->getConfig()->getTitle()->prepend(__('Edit Gift Card'));

        } else {
            $_resultPage->getConfig()->getTitle()->prepend(__('New Gift Card'));
        }
        return $_resultPage;
    }
}
