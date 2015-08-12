<?php

/**
 * Class RK_TypeCMS_Helper_Data
 */
class RK_TypeCMS_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Media directory for the image/file fields.
     */
    const TYPECMS_PATH = 'typecms';

    /**
     * @var array
     */
    protected $_attributeTypeToBackendType = array(
        'text' => 'varchar',
        'select' => 'varchar',
        'yesno' => 'int',
        'textarea' => 'text',
        'editor' => 'text',
        'image' => 'varchar',
        'file' => 'varchar',
    );

    /**
     * @param string $type
     * @return string
     */
    public function attributeTypeToBackendType($type)
    {
        return isset($this->_attributeTypeToBackendType[$type]) ? $this->_attributeTypeToBackendType[$type] : $type;
    }

    /**
     * @var array
     */
    protected $_attributeTypeToFieldType = array(
        'int' => 'text',
        'image' => 'file',
        'yesno' => 'select',
    );

    /**
     * @param string $type
     * @return string
     */
    public function attributeTypeToFieldType($type)
    {
        return isset($this->_attributeTypeToFieldType[$type]) ? $this->_attributeTypeToFieldType[$type] : $type;
    }

    /**
     * Setup all the configured attributes from the config.
     */
    public function setupAttributes()
    {
        $config = Mage::getSingleton('typecms/config');
        $setup = Mage::getResourceModel('typecms/setup', 'typecms_setup');
        $pageTypes = $config->getPageTypes();
        $attributes = array();

        foreach ($pageTypes as $pageTypeCode => $pageType) {
            $_attributes = $config->getAttributes($pageTypeCode);
            foreach ($_attributes as $attributeCode => $attribute) {
                if (!isset($attributes[$attributeCode])) $attributes[$attributeCode] = array();
                $attributes[$attributeCode][] = $attribute;
            }
        }

        foreach ($attributes as $attributeCode => $_attributes) {
            if (count($_attributes) > 1) {
                $type = $this->attributeTypeToBackendType($_attributes[0]['type']);
                for ($i = 1; isset($_attributes[$i]); $i++) {
                    $attribute = $_attributes[$i];
                    $backendType = $this->attributeTypeToBackendType($attribute['type']);
                    if ($type !== $backendType) {
                        Mage::throwException('Conflicting backend types for attribute "' . $attributeCode . '": ' . $type . ' (' . $_attributes[0]['type'] . ') does not equal ' . $backendType . ' (' . $attribute['type'] . ')');
                    }
                }
            } else {
                $attribute = $_attributes[0];
                $backendType = $this->attributeTypeToBackendType($attribute['type']);
                $attributeEntity = $setup->getAttribute(RK_TypeCMS_Model_Page::ENTITY, $attributeCode);
                if (!$attributeEntity) {
                    $setup->addAttribute(RK_TypeCMS_Model_Page::ENTITY, $attributeCode, array(
                        'type' => $backendType,
                    ));
                } elseif ($attributeEntity['backend_type'] != $backendType) {
                    $setup->removeAttribute(RK_TypeCMS_Model_Page::ENTITY, $attributeCode);
                    $backendType = $this->attributeTypeToBackendType($attribute['type']);
                    $setup->addAttribute(RK_TypeCMS_Model_Page::ENTITY, $attributeCode, array(
                        'type' => $backendType,
                    ));
                }
            }
        }

        /* @var $collection Mage_Eav_Model_Resource_Entity_Attribute_Collection */
        $collection = Mage::getSingleton('eav/config')
            ->getEntityType(RK_TypeCMS_Model_Page::ENTITY)->getAttributeCollection();
        $collection->addFieldToFilter('backend_type', array('neq' => 'static'))
            ->addFieldToFilter('attribute_code', array('nin' => array_keys($attributes)));

        foreach ($collection as $attribute) {
            $attribute->delete();
        }

        $setup->getConnection()->commit();
    }

    /**
     * @return string
     */
    public function getBaseImageDir()
    {
        return Mage::getBaseDir('media') . DS . self::TYPECMS_PATH . DS;
    }

    /**
     * @return string
     */
    public function getBaseImageUrl()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . self::TYPECMS_PATH . '/';
    }

    /**
     * Checks if a table exists to determine whether CleverCMS is installed.
     * Reason I use this method is because of the many namespace changes CleverCMS has had.
     * @return bool
     */
    public function hasCleverCMS()
    {
        $setup = Mage::getResourceModel('typecms/setup', 'typecms_setup');
        return $setup->tableExists(Mage::getSingleton("core/resource")->getTableName('bubble_cms_page_tree'));
    }
}
