<?php

/**
 * Class RK_TypeCMS_Model_Page_Type_Abstract
 */
abstract class RK_TypeCMS_Model_Page_Type_Abstract extends Varien_Object
{

    /**
     * @var array
     */
    protected $_attributes;

    /**
     * @param Varien_Data_Form $form
     * @param RK_TypeCMS_Model_Page $page
     */
    abstract function init(Varien_Data_Form $form, RK_TypeCMS_Model_Page $page);

    /**
     * @return string
     */
    protected function getHandle()
    {
        if (!isset($this->_handle)) {
            $this->_handle = Mage::getSingleton('typecms/config')->getHandle($this->getPageType());
        }
        return $this->_handle;
    }

    /**
     * @return array
     */
    protected function getAttributes()
    {
        if (!isset($this->_attributes)) {
            $this->_attributes = Mage::getSingleton('typecms/config')->getAttributes($this->getPageType());
        }
        return $this->_attributes;
    }
}
