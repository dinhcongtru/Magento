<?php

namespace Mageplaza\DevelopmentGuide\Model\ResourceModel;


class Cate extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('mageplaza_blog_category', 'category_id');
    }

}
