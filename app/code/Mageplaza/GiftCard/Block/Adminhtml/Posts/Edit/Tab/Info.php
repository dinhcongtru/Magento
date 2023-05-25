<?php

namespace Mageplaza\GiftCard\Block\Adminhtml\Posts\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Info extends Generic implements TabInterface
{
    protected $helperData;
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $_adminSession;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Backend\Model\Auth\Session $adminSession
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry             $registry,
        \Magento\Framework\Data\FormFactory     $formFactory,
        \Magento\Backend\Model\Auth\Session     $adminSession,
        \Mageplaza\GiftCard\Helper\Data         $helperData,
        array                                   $data = []
    )
    {
        $this->helperData = $helperData;
        $this->_adminSession = $adminSession;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare the form.
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('giftcard');
        $isElementDisabled = false;
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('page_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Gift Card Information')]);
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id', 'value' => $model->getId()]);
            $fieldset->addField(
                'code',
                'text',
                [
                    'name' => 'code',
                    'label' => __('Code'),
                    'title' => __('Code'),
                    'required' => false,
                    'disabled' => true,
                ]
            );
            $fieldset->addField(
                'balance',
                'text',
                [
                    'name' => 'balance',
                    'label' => __('Balance'),
                    'title' => __('Balance'),
                    'required' => true,
                    'disabled' => $isElementDisabled,
                ]
            );
            $fieldset->addField(
                'create_from',
                'text',
                [
                    'name' => 'create_from',
                    'label' => __('Create from'),
                    'title' => __('Create from'),
                    'required' => false,
                    'disabled' => true,
                ]
            );
        } else {
            $fieldset->addField(
                'codelength',
                'text',
                [
                    'name' => 'codelength',
                    'label' => __('Code length'),
                    'title' => __('Code length'),
                    'value' => $this->helperData->getGeneralConfig('display_text'),
                    'required' => false,
                    'disabled' => false,
                ]
            );
            $fieldset->addField(
                'balance',
                'text',
                [
                    'name' => 'balance',
                    'label' => __('Balance'),
                    'title' => __('Balance'),
                    'required' => true,
                    'disabled' => $isElementDisabled,
                ]
            );
        }
        $form->addValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Return Tab label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Blog Information');
    }

    /**
     * Return Tab title
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Blog Information');
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
}
