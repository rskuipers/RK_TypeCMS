<?php

class RK_TypeCMS_Model_Resource_Setup extends Mage_Eav_Model_Entity_Setup
{

    public function getDefaultEntities() {
        return array(
            RK_TypeCMS_Model_Page::ENTITY => array(
                'entity_model' => 'typecms/page',
                'table' => 'typecms/page',
                'attributes' => array(
                    'page_type' => array('type' => 'static'),
                ),
            ),
        );
    }

}