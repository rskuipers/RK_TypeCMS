<?php

class RickDev_TypeCMS_Model_Observer {

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
        $pageTypeInstance->init($form);
    }

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
        $attributes = $page->getData('typecms');
        if (isset($attributes)) $pageType->addData($attributes);
        $pageType->save();
    }

}