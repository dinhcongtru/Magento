<?php

namespace Mageplaza\GiftCard\Controller\Adminhtml\Posts;

use  Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Create extends Action
{
    const ADMIN_RESOURCE = 'Mageplaza_GiftCard::posts_create';
    protected $resultPageFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Create new news action
     * @return void
     */
    public function execute()
    {
        if (!$this->_isAllowed()) {
            $this->messageManager->addErrorMessage(__('You don\'t have permission to access this page.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/');
        }
        $this->_forward('edit');
    }
}
