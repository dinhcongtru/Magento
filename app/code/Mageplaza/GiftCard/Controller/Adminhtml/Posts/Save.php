<?php

namespace Mageplaza\GiftCard\Controller\Adminhtml\Posts;

use Magento\Backend\App\Action\Context;
use Mageplaza\GiftCard\Model\GiftCardFactory;
use Magento\Framework\Registry;
use Mageplaza\GiftCard\Controller\Adminhtml\Post;

class Save extends Post
{
    const ADMIN_RESOURCE = 'Mageplaza_GiftCard::posts_save';
    public $_giftCardFactory;
    public $_registry;

    public function __construct(GiftCardFactory $giftCardFactory,
                                Registry        $coreRegistry,
                                Context         $context)
    {
        $this->_giftCardFactory = $giftCardFactory;
        $this->_registry = $coreRegistry;
        parent::__construct($giftCardFactory, $coreRegistry, $context);
    }

    public function execute()
    {
        if (!$this->_isAllowed()) {
            $this->messageManager->addErrorMessage(__('You don\'t have permission to access this page.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
        $post = $this->initPost();
        if (!$post) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*');
            return $resultRedirect;
        }
        $param = $this->getRequest()->getParams();
        $datas = [
            'balance' => $this->getRequest()->getParam('balance')
        ];
        $dataGift = [
            'code' => "",
            'balance' => $this->getRequest()->getParam('balance'),
            'create_from' => 'Magento - Admin'
        ];
        $id = $this->getRequest()->getParam('id');
        $giftcard = $this->_giftCardFactory->create();
        $giftcard->Load($id);
        if (isset($param['back'])) {
            if ($giftcard->getId()) {
                try {
                    $giftcard->addData($datas)->save();
                    $this->messageManager->addSuccessMessage('Gift Card updated Successfully');
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage('Gift Card failed to update' . $e->getMessage());
                }
            }
            $this->_redirect('*/*/edit', ['id' => $giftcard->getId()]);
        } else {
            if ($giftcard->getId()) {
                try {
                    $giftcard->addData($datas)->save();
                    $this->messageManager->addSuccessMessage('Gift Card updated Successfully');
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage('Gift Card failed to update' . $e->getMessage());
                }
            } else {
                try {
                    $dataGift['code'] = $this->randomCodeLength($param['codelength']);
                    $giftcard->addData($dataGift)->save();
                    $this->messageManager->addSuccessMessage('Gift Card has been post.');
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage('Gift Card add Error data' . $e->getMessage());
                }
            }
            $this->_redirect('*/*/');
        }
    }

    public function randomCodeLength($count)
    {
        $characters = 'ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789';
        $code = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $count; $i++) {
            $code .= $characters[random_int(0, $max)];
        }
        return $code;
    }
}
