<?php

/**
 * Class RK_TypeCMS_Block_Page
 */
class RK_TypeCMS_Block_Page extends Mage_Cms_Block_Page
{

    /**
     * @return string
     */
    protected function _toHtml()
    {
        /* @var RK_TypeCMS_Model_Page $page */
        $page = Mage::getModel('typecms/page')->load($this->getPage()->getId());
        if ($page->getId()) {
            $blockType = $page->getPageTypeInstance()->getBlock();
            $block = $this->getLayout()->createBlock($blockType, 'typecms_block');
            $block->setTemplate($page->getPageTypeInstance()->getTemplate());
            $block->addData($page->getFilteredData());
            $block->addData($this->getPage()->getData());
            $block->setPage($this->getPage());
            return $block->toHtml();
        }
        return parent::_toHtml();
    }
}
