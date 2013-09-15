<?php

class RK_TypeCMS_Model_Page_Type_Default extends RK_TypeCMS_Model_Page_Type_Abstract
{

    public function init(Varien_Data_Form $form)
    {
        $attributes = $this->getAttributes();

        if (!count($attributes)) return;
        $fieldset = $form->addFieldset('typecms_fields', array(
            'legend' => Mage::helper('typecms')->__('TypeCMS Fields'),
            'class' => 'fieldset-wide'
        ));

        foreach ($attributes as $code => $attribute) {
            $type = Mage::helper('typecms')->attributeTypeToFieldType($attribute['type']);
            $label = Mage::helper('typecms')->__($attribute['label']);
            if ($type == 'editor') {
                $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
                    array('tab_id' => 'main')
                );
                $fieldset->addField('typecms_' . $code, $type, array(
                    'name'      => 'typecms[' . $code . ']',
                    'label'     => $label,
                    'style'     => 'height:36em;',
                    'config'    => $wysiwygConfig
                ));
            } else {
                $fieldset->addField('typecms_' . $code, $type, array(
                    'name' => 'typecms[' . $code . ']',
                    'label' => $label,
                ));
            }
        }
    }

}