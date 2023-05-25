<?php

namespace Mageplaza\DevelopmentGuide\Plugin;

class CategoryName
{
    public function beforeExecute(\Magento\Catalog\Controller\Category\View $subject)
    {
        $category = $subject->getRequest()->getParam('id');
        if ($category) {
            $subject->getResponse()->setHeader('category_name', 'Mageplaza (' . $category . ')');
        }
    }
}
