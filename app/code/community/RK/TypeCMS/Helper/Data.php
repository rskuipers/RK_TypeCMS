<?php

class RK_TypeCMS_Helper_Data extends Mage_Core_Helper_Abstract
{

    const TYPECMS_PATH = 'typecms';

    protected $_attributeTypeToBackendType = array(
        'text' => 'varchar',
        'select' => 'varchar',
        'yesno' => 'int',
        'textarea' => 'text',
        'editor' => 'text',
        'image' => 'varchar',
        'file' => 'varchar',
    );

    public function attributeTypeToBackendType($type)
    {
        return isset($this->_attributeTypeToBackendType[$type]) ? $this->_attributeTypeToBackendType[$type] : $type;
    }

    protected $_attributeTypeToFieldType = array(
        'textarea' => 'textarea',
        'int' => 'text',
        'yesno' => 'select',
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
                $backendType = $this->attributeTypeToBackendType($attribute['type']);
                if (!$attributeEntity) {
                    $setup->addAttribute(RK_TypeCMS_Model_Page::ENTITY, $attributeCode, array(
                        'type' => $backendType,
                    ));
                } elseif ($attributeEntity['backend_type'] != $backendType) {
                    Mage::throwException('Conflicting backend type for attribute "' . $attributeCode . '": ' . $attributeEntity['backend_type'] . ' does not equal ' . $backendType . ' (' . $attribute['type'] . ')');
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

    public function getBaseImageDir()
    {
        return Mage::getBaseDir('media') . DS . self::TYPECMS_PATH . DS;
    }

    public function getBaseImageUrl()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . self::TYPECMS_PATH . '/';
    }

}