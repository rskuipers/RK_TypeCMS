<?php

/**
 * Class RK_TypeCMS_Block_Page_Type_Collection
 */
class RK_TypeCMS_Block_Page_Type_Collection extends RK_TypeCMS_Block_Page_Type_Abstract
{

    /**
     * @return array
     */
    public function getChildren()
    {
        if (Mage::helper('typecms')->hasCleverCMS()) {
            return $this->getChildrenCleverCMS();
        }
        return array(); // @TODO find children by identifier.
    }

    /**
     * @return Varien_Data_Collection
     */
    public function getChildrenCleverCMS()
    {
        $page = $this->getPage();
        $children = $page->getChildren();

        $entityType = Mage::getModel('eav/entity_type')->loadByCode(RK_TypeCMS_Model_Page::ENTITY);
        $attributes = $entityType->getAttributeCollection();
        $entityTable = $children->getTable($entityType->getEntityTable());

        $index = 1;
        foreach ($attributes->getItems() as $attribute){
            $alias = 'table' . $index;
            if ($attribute->getBackendType() != 'static'){
                $table = $entityTable . '_' . $attribute->getBackendType();
                $field = $alias.'.value';
                $children->getSelect()
                    ->joinLeft(array($alias => $table),
                        'main_table.page_id = ' . $alias . '.entity_id and ' . $alias . '.attribute_id = ' . $attribute->getAttributeId(),
                        array($attribute->getAttributeCode() => $field)
                    );
            }
            $index++;
        }
        $children->getSelect()->joinLeft($entityTable, 'main_table.page_id = '.$entityTable.'.entity_id');

        return $children;
    }
}
