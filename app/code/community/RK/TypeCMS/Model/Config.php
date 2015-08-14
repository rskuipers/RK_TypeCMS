<?php

/**
 * Class RK_TypeCMS_Model_Config
 */
class RK_TypeCMS_Model_Config
{

    /**
     * Config path to the defined page types
     */
    const XML_PATH_PAGE_TYPES = 'global/page/types';

    /**
     * @var array
     */
    protected $_pageTypes;

    /**
     * @return array
     */
    public function getPageTypes()
    {
        if (!isset($this->_pageTypes)) {
            $this->_pageTypes = Mage::getConfig()->getNode(self::XML_PATH_PAGE_TYPES)->asArray();
            if (isset($this->_pageTypes['label'])) $this->_pageTypes['label'] = Mage::helper('typecms')->__($this->_pageTypes['label']);
        }
        return $this->_pageTypes;
    }

    /**
     * @param string $type
     * @return array
     */
    public function getPageType($type)
    {
        $types = $this->getPageTypes();
        if (!isset($types[$type])) $type = Mage::getSingleton('typecms/source_type')->getDefaultValue();
        return $types[$type];
    }

    /**
     * @param string $pageType
     * @return string
     */
    public function getHandle($pageType)
    {
        $node = Mage::getConfig()->getNode(self::XML_PATH_PAGE_TYPES)->descend($pageType);

        if ($node && $node = $node->descend('handle')) {
            return $node->asString();
        }
        return false;
    }

    /**
     * @param string $pageType
     * @return array
     */
    public function getAttributes($pageType)
    {
        $node = Mage::getConfig()->getNode(self::XML_PATH_PAGE_TYPES)->descend($pageType);

        if ($node && $node = $node->descend('attributes')) {
            return $node->hasChildren() ? $node->asArray() : array();
        }
        return array();
    }
}
