<?php

/**
 * Class RK_TypeCMS_Model_Page
 */
class RK_TypeCMS_Model_Page extends Mage_Core_Model_Abstract
{

    /**
     * Entity type
     */
    const ENTITY = 'typecms_page';

    /**
     * @var string
     */
    protected $_eventPrefix = 'typecms_page';
    /**
     * @var string
     */
    protected $_eventObject = 'page';

    /**
     * @var RK_TypeCMS_Model_Page_Type_Abstract
     */
    protected $_pageTypeInstance;


    /**
     * Construct
     */
    public function __construct()
    {
        $this->_init('typecms/page');
    }

    /**
     * @return RK_TypeCMS_Model_Page_Type_Abstract
     */
    public function getPageTypeInstance()
    {
        $default = Mage::getSingleton('typecms/config')->getPageType('default');
        if (!isset($this->_pageTypeInstance)) {
            $pageType = Mage::getSingleton('typecms/config')->getPageType($this->getPageType());
            $this->_pageTypeInstance = Mage::getModel($pageType['model']);
            $this->_pageTypeInstance->addData($default);
            $this->_pageTypeInstance->addData($pageType);
            $this->_pageTypeInstance->setPageType($this->getPageType());
        }
        return $this->_pageTypeInstance;
    }

    /**
     * @return array
     */
    public function getFilteredData()
    {
        $data = $this->getData();
        unset($data['entity_id']);
        return $data;
    }

    /**
     * Event triggered before save()
     */
    protected function _beforeSave()
    {
        if (!$this->getPageType()) {
            Mage::throwException('Missing Page Type');
        }
        return parent::_beforeSave();
    }
}
