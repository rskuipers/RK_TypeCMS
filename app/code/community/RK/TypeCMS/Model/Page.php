<?php

class RK_TypeCMS_Model_Page extends Mage_Core_Model_Abstract
{

    const ENTITY = 'typecms_page';
    const DEFAULT_PAGE_TYPE = 'typecms/page_type_default';

    protected $_eventPrefix = 'typecms_page';
    protected $_eventObject = 'page';

    protected $_pageTypeInstance;


    public function __construct()
    {
        $this->_init('typecms/page');
    }

    public function getPageTypeInstance()
    {
        if (!isset($this->_pageTypeInstance)) {
            $pageType = Mage::getSingleton('typecms/config')->getPageType($this->getPageType());
            $this->_pageTypeInstance = Mage::getModel(isset($pageType['model']) ? $pageType['model'] : self::DEFAULT_PAGE_TYPE);
            $this->_pageTypeInstance->setPageType($this->getPageType());
            $this->_pageTypeInstance->addData($pageType);
        }
        return $this->_pageTypeInstance;
    }

    public function getFilteredData()
    {
        $data = $this->getData();
        unset($data['entity_id']);
        return $data;
    }

    protected function _beforeSave()
    {
        if (!$this->getPageType()) {
            Mage::throwException('Missing Page Type');
        }
    }

}