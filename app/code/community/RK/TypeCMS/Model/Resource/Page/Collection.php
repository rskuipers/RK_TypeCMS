<?php

class RK_TypeCMS_Model_Resource_Page_Collection extends Mage_Eav_Model_Entity_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('typecms/page');
    }

    protected function _initSelect()
    {
        $this->getSelect()->from(array('e' => $this->getEntity()->getEntityTable()));

        return $this;
    }

}