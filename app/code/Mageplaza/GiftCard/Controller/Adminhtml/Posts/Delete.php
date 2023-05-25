<?php

namespace Mageplaza\GiftCard\Controller\Adminhtml\Posts;

use Magento\Backend\App\Action;
use Mageplaza\GiftCard\Model\GiftCardFactory;

class Delete extends Action
{
    const ADMIN_RESOURCE = 'Mageplaza_GiftCard::posts_delete';
    protected $_giftCardFactory;

    public function __construct(
        Action\Context  $context,
        GiftCardFactory $giftCardFactory
    )
    {
        $this->_giftCardFactory = $giftCardFactory;
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
        $post = $this->_giftCardFactory->create();
        $id = $this->getRequest()->getParam('id');
        $post->load($id);
        try {
            if ($post->getId()) {
                $post->delete();
                $this->messageManager->addSuccessMessage('Success to delete the post');
            } else {
                $this->messageManager->addErrorMessage('No data to delete the post');
            }
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage('Success to delete the post' . $e->getMessage());
        } finally {
            $this->_redirect('*/*/');
        }
    }
}
