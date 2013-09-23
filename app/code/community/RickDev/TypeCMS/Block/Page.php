<?php

class RickDev_TypeCMS_Block_Page extends Mage_Cms_Block_Page
{

    protected function _toHtml()
    {
        $page = Mage::getModel('typecms/page')->load($this->getPage()->getId());
        if ($page->getId() && $page->getPageTypeInstance()->getTemplate() != '') {
            /* @var $block Mage_Core_Block_Template */
            $block = $this->getLayout()->createBlock('core/template', 'typecms_block');
            $block->setTemplate($page->getPageTypeInstance()->getTemplate());
            $block->addData($page->getFilteredData());
            $block->addData($this->getPage()->getData());
            return $block->toHtml();
        }
        return parent::_toHtml();
    }

}