<?php

/**
 * Class RK_TypeCMS_Model_Page_Type_Default
 */
class RK_TypeCMS_Model_Page_Type_Default extends RK_TypeCMS_Model_Page_Type_Abstract
{

    /**
     * @param Varien_Data_Form $form
     * @param RK_TypeCMS_Model_Page $page
     */
    public function init(Varien_Data_Form $form, RK_TypeCMS_Model_Page $page)
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

            if ($type == 'file') {
                $field = $fieldset->addField('typecms_' . $code, 'file', array(
                    'name' => 'typecms[' . $code . ']',
                    'label' => $label,
                    'after_element_html' => '<script type="text/javascript">setTimeout(function(){$(\'edit_form\').enctype = \'multipart/form-data\';}, 50);</script>',
                ));
                if ($page->getData($code)) {
                    if ($attribute['type'] == 'image') {
                        $field->setData('after_element_html', '<br />
                        <img src="' . Mage::helper('typecms')->getBaseImageUrl() . $page->getData($code) . '" width="300" /><br />
                        <label><input type="checkbox" name="typecms[' . $code . '_delete]" value="1" /> Delete image</label>' . $field->getData('after_element_html'));
                    } else {
                        $field->setData('after_element_html', '<br />
                        ' . Mage::helper('typecms')->escapeHtml($page->getData($code)) . '<br />
                        <label><input type="checkbox" name="typecms[' . $code . '_delete]" value="1" /> Delete file</label>' . $field->getData('after_element_html'));
                    }
                }
                $form->setData('enctype', 'multipart/form-data');
            } else {
                $field = $fieldset->addField('typecms_' . $code, $type, array(
                    'name' => 'typecms[' . $code . ']',
                    'label' => $label,
                ));
            }

            if ($attribute['type'] == 'yesno') {
                $attribute['options'] = array(
                    0 => 'No',
                    1 => 'Yes',
                );
            }

            if ($type == 'editor') {
                $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
                    array('tab_id' => 'main')
                );
                $field->addData(array(
                    'style' => 'height:36em;',
                    'config' => $wysiwygConfig
                ));
            } elseif ($type == 'select') {
                $field->addData(array(
                    'values' => $attribute['options'],
                ));
            }
        }
    }
}
