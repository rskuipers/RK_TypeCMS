<?php

/**
 * Class RK_TypeCMS_Model_Resource_Page
 */
class RK_TypeCMS_Model_Resource_Page extends Mage_Eav_Model_Entity_Abstract
{

    /**
     * Construct
     */
    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');

        $this->setType(RK_TypeCMS_Model_Page::ENTITY);

        $this->setConnection(
            $resource->getConnection('typecms_read'),
            $resource->getConnection('typecms_write')
        );
    }
}
