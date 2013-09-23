<?php

class RickDev_TypeCMS_Model_Resource_Page extends Mage_Eav_Model_Entity_Abstract
{

    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');

        $this->setType(RickDev_TypeCMS_Model_Page::ENTITY);

        $this->setConnection(
            $resource->getConnection('typecms_read'),
            $resource->getConnection('typecms_write')
        );
    }

}