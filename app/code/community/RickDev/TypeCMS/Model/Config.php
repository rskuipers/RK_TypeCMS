<?php

class RickDev_TypeCMS_Model_Config
{

    const XML_PATH_PAGE_TYPES = 'global/page/types';

    protected $_pageTypes;

    public function getPageTypes()
    {
        if (!isset($this->_pageTypes)) {
            $this->_pageTypes = Mage::getConfig()->getNode(self::XML_PATH_PAGE_TYPES)->asArray();
            if (isset($this->_pageTypes['label'])) $this->_pageTypes['label'] = Mage::helper('typecms')->__($this->_pageTypes['label']);
        }
        return $this->_pageTypes;
    }

    public function getPageType($type)
    {
        $types = $this->getPageTypes();
        if (!isset($types[$type])) $type = Mage::getSingleton('typecms/source_type')->getDefaultValue();
        return $types[$type];
    }

    public function getAttributes($pageType)
    {
        $node = Mage::getConfig()->getNode(self::XML_PATH_PAGE_TYPES)->descend($pageType);

        if ($node && $node = $node->descend('attributes')) {
            return $node->hasChildren() ? $node->asArray() : array();
        }
        return array();
    }

}