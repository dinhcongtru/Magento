<?php

namespace Mageplaza\GiftCard\Controller\Adminhtml\Posts;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Mageplaza\GiftCard\Model\ResourceModel\GiftCard\CollectionFactory;

/**
 * Class MassDelete
 * @package Mageplaza\Blog\Controller\Adminhtml\Tag
 */
class MassDelete extends Action
{
    const ADMIN_RESOURCE = 'Mageplaza_GiftCard::posts_massdelete';
    /**
     * @var Filter
     */
    public $filter;
    /**
     * @var CollectionFactory
     */
    public $collectionFactory;

    /**
     * MassDelete constructor.
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context           $context,
        Filter            $filter,
        CollectionFactory $collectionFactory
    )
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;

        parent::__construct($context);
    }

    /**
     * @return $this|ResponseInterface|ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        if (!$this->_isAllowed()) {
            $this->messageManager->addErrorMessage(__('You don\'t have permission to access this page.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        try {
            $collection->walk('delete');
            $this->messageManager->addSuccessMessage(__('Gift Card has been deleted.'));
        } catch (Exception $e) {
            $this->messageManager->addSuccessMessage(__('Something wrong when delete Gift Card.'));
        }
        /**
         * @var Redirect $resultRedirect
         */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');

    }
}
