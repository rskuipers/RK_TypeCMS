<?php

class RK_TypeCMS_Helper_Data extends Mage_Core_Helper_Abstract
{

    protected $_attributeTypeToDbType = array(
        'text' => 'varchar',
        'textarea' => 'text',
        'editor' => 'text',
    );

    public function attributeTypeToDbType($type)
    {
        return isset($this->_attributeTypeToDbType[$type]) ? $this->_attributeTypeToDbType[$type] : $type;
    }

    protected $_attributeTypeToFieldType = array(
        'textarea' => 'textarea',
        'int' => 'text',
    );

    public function attributeTypeToFieldType($type)
    {
        return isset($this->_attributeTypeToFieldType[$type]) ? $this->_attributeTypeToFieldType[$type] : $type;
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
                        'type' => $this->attributeTypeToDbType($attribute['type']),
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