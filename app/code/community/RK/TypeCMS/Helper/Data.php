<?php

class RK_TypeCMS_Helper_Data extends Mage_Core_Helper_Abstract
{

    protected $_dbTypeToForm = array(
        'varchar' => 'text',
        'text' => 'editor',
        'int' => 'text',
    );

    public function dbTypeToForm($type)
    {
        return $this->_dbTypeToForm[$type];
    }

    public function setupAttributes()
    {
        $config = Mage::getSingleton('typecms/config');
        $setup = Mage::getResourceModel('typecms/setup', 'typecms_setup');
        $pageTypes = $config->getPageTypes();
        $attributes = array();
        foreach ($pageTypes as $pageTypeCode => $pageType) {
            $attributes = $config->getAttributes($pageTypeCode);
            foreach ($attributes as $attributeCode => $attribute) {
                $attributeEntity = $setup->getAttribute(RK_TypeCMS_Model_Page::ENTITY, $attributeCode);
                if (!$attributeEntity) {
                    $setup->addAttribute(RK_TypeCMS_Model_Page::ENTITY, $attributeCode, array(
                        'type' => $attribute['type'],
                    ));
                }
                $attributes[] = $attributeCode;
            }
        }

        /* @var $collection Mage_Eav_Model_Resource_Entity_Attribute_Collection */
        $collection = Mage::getSingleton('eav/config')
            ->getEntityType(RK_TypeCMS_Model_Page::ENTITY)->getAttributeCollection();
        $collection->addFieldToFilter('backend_type', array('neq' => 'static'))
            ->addFieldToFilter('attribute_code', array('nin' => $attributes));

        foreach ($collection as $attribute) {
            $attribute->delete();
        }

        $setup->getConnection()->commit();
    }

}