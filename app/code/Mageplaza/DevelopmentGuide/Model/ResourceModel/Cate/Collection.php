<?php

namespace Mageplaza\DevelopmentGuide\Model\ResourceModel\Cate;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'category_id';
    protected $_eventPrefix = 'mageplaza_blog_category_post_collection';
    protected $_eventObject = 'post_collection';

    /**
     * Define resource model
     *x
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mageplaza\DevelopmentGuide\Model\Cate', 'Mageplaza\DevelopmentGuide\Model\ResourceModel\Cate');
    }

}
