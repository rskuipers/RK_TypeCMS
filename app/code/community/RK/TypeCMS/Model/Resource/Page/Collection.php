<?php

/**
 * Class RK_TypeCMS_Model_Resource_Page_Collection
 */
class RK_TypeCMS_Model_Resource_Page_Collection extends Mage_Eav_Model_Entity_Collection_Abstract
{

    /**
     * Construct
     */
    protected function _construct()
    {
        $this->_init('typecms/page');
    }

    /**
     * @return $this
     */
    protected function _initSelect()
    {
        $this->getSelect()->from(array('e' => $this->getEntity()->getEntityTable()));

        return $this;
    }
}
