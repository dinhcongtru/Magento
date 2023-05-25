<?php

namespace Mageplaza\GiftCard\Controller\Adminhtml\Posts;
class Index extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Mageplaza_GiftCard::posts_index';
    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context        $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        if (!$this->_isAllowed()) {
            $this->messageManager->addErrorMessage(__('You don\'t have permission to access this page.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Gift Cards')));
        return $resultPage;
    }


}
