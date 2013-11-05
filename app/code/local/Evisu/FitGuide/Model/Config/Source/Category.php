<?php
class Evisu_FitGuide_Model_Config_Source_Category extends RonisBT_Cms_Model_Entity_Attribute_Source_Options
{
    public function __construct(){
        $categories = Mage::getModel('catalog/category')
            ->getTreeModel()
            ->getCollection('parent_id')
            ->addAttributeToSelect('name');
        /* @var $rootCategory Mage_Catalog_Model_Resource_Category_Collection */
        foreach($categories as $category)
        {
            if($category->getLevel() == 3)
            {
                $this->_optionList[$category->getId()] = $categories->getItemById($category->getParentId())->getName() . ' / ' .$category->getName();
            }
        }
    }
}
