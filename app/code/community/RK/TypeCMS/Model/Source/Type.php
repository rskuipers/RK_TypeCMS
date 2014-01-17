<?php

/**
 * Class RK_TypeCMS_Model_Source_Type
 */
class RK_TypeCMS_Model_Source_Type
{

    /**
     * Page type options
     *
     * @var array
     */
    protected $_options = null;

    /**
     * Default option
     * @var string
     */
    protected $_defaultValue = null;

    /**
     * Retrieve page type options
     *
     * @return array
     */
    public function getOptions()
    {
        if ($this->_options === null) {
            $this->_options = array();
            foreach (Mage::getSingleton('typecms/config')->getPageTypes() as $code => $type) {
                $this->_options[$code] = $type['label'];
                if ($code === 'default') {
                    $this->_defaultValue = $code;
                }
            }
        }

        return $this->_options;
    }

    /**
     * Retrieve page layout options array
     *
     * @param $withEmpty bool Add an empty option
     * @return array
     */
    public function toOptionArray($withEmpty = false)
    {
        $options = array();

        foreach ($this->getOptions() as $value => $label) {
            $options[] = array(
                'label' => $label,
                'value' => $value,
            );
        }

        if ($withEmpty) {
            array_unshift($options, array('value' => '', 'label' => Mage::helper('typecms')->__('-- Please Select --')));
        }

        return $options;
    }

    /**
     * Default options value getter
     * @return string
     */
    public function getDefaultValue()
    {
        $this->getOptions();
        return $this->_defaultValue;
    }
}
