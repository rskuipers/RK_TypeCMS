<?php

/**
 * Class RK_TypeCMS_Model_Observer
 */
class RK_TypeCMS_Model_Observer {

    /**
     * @param Varien_Event_Observer $observer
     */
    public function adminhtmlCmsPageEditTabMainPrepareForm($observer)
    {
        /* @var $page Mage_Cms_Model_Page */
        $page = Mage::registry('cms_page');
        /* @var $form Varien_Data_Form */
        $form = $observer->getForm();
        /* @var $fieldset Varien_Data_Form_Element_Fieldset */
        $fieldset = $form->getElement('base_fieldset');
        $pageTypes = Mage::getSingleton('typecms/source_type')->toOptionArray();
        $fieldset->addField('page_type', 'select', array(
            'name' => 'page_type',
            'label' => Mage::helper('typecms')->__('Page Type'),
            'values' => $pageTypes
        ));
        $model = Mage::getModel('typecms/page')->load($page->getId());
        if (!$model->getPageType()) {
            $page->setPageType(Mage::getSingleton('typecms/source_type')->getDefaultValue());
            $model->setPageType(Mage::getSingleton('typecms/source_type')->getDefaultValue());
        } else {
            $page->setPageType($model->getPageType());
        }
        $values = array();
        foreach ($model->getFilteredData() as $key => $value) {
            $values['typecms_' . $key] = $value;
        }
        $page->addData($values);
        $pageTypeInstance = $model->getPageTypeInstance();
        $pageTypeInstance->init($form, $model);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function cmsPageSaveAfter($observer)
    {
        /* @var $page Mage_Cms_Model_Page */
        $page = $observer->getObject();

        Mage::helper('typecms')->setupAttributes();

        $pageType = Mage::getModel('typecms/page')->load($page->getId());
        if (!$pageType->getId()) {
            $pageType->setId($page->getId());
        }
        $pageType->setPageType($page->getPageType());
        $data = $page->getData('typecms');
        if (isset($data)) $pageType->addData($data);

        $config = Mage::getSingleton('typecms/config');
        $attributes = $config->getAttributes($pageType->getData('page_type'));

        foreach ($attributes as $attributeCode => $attribute) {
            if (in_array($attribute['type'], array('image', 'file'))) {
                if ($pageType->getData($attributeCode . '_delete') == '1') {
                    self::deleteImage($pageType->getData($attributeCode));
                    $pageType->setData($attributeCode, null);
                }
                $file = $this->handleUpload($attributeCode, $attribute['type']);
                if ($file && $file !== $pageType->getData($attributeCode)) {
                    self::deleteImage($pageType->getData($attributeCode));
                }
                if ($file) $pageType->setData($attributeCode, $file);
            }
        }

        $pageType->save();
    }

    /**
     * @param string $attributeCode
     * @param string $type
     * @return bool
     */
    protected static function handleUpload($attributeCode, $type)
    {
        if (!isset($_FILES)) return false;
        $adapter = new Zend_File_Transfer_Adapter_Http();
        if ($adapter->isUploaded('typecms_' . $attributeCode . '_')) {
            if (!$adapter->isValid('typecms_' . $attributeCode . '_')) {
                Mage::throwException(Mage::helper('typecms')->__('Uploaded ' . $type . ' is invalid'));
            }
            $upload = new Varien_File_Uploader('typecms[' . $attributeCode . ']');
            $upload->setAllowCreateFolders(true);
            if ($type == 'image') {
                $upload->setAllowedExtensions(array('jpg', 'gif', 'png'));
            }
            $upload->setAllowRenameFiles(true);
            $upload->setFilesDispersion(false);
            try {
                if ($upload->save(Mage::helper('typecms')->getBaseImageDir())) {
                    return $upload->getUploadedFileName();
                }
            } catch (Exception $e) {
                Mage::throwException('Uploaded ' . $type . ' is invalid');
            }
        }
        return false;
    }

    /**
     * @param string $file
     * @return bool
     */
    protected static function deleteImage($file)
    {
        $io = new Varien_Io_File();
        return $io->rm(Mage::helper('typecms')->getBaseImageDir() . $file);
    }

}