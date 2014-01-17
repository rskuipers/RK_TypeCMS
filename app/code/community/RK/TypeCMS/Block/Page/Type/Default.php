<?php

/**
 * Class RK_TypeCMS_Block_Page_Type_Default
 */
class RK_TypeCMS_Block_Page_Type_Default extends RK_TypeCMS_Block_Page_Type_Abstract
{

    /**
     * Content cache
     * @var string
     */
    protected $_content;

    /**
     * @return string
     */
    public function getContent()
    {
        if (!isset($this->_content)) {
            /* @var $helper Mage_Cms_Helper_Data */
            $helper = Mage::helper('cms');
            $processor = $helper->getPageTemplateProcessor();
            $this->_content = $processor->filter($this->getData('content'));
        }
        return $this->_content;
    }
}
