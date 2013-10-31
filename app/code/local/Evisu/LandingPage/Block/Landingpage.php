<?php

class Evisu_LandingPage_Block_Landingpage extends RonisBT_Cms_Block_Page
{

    public function getSections()
    {
        $sectionBlocks = new Varien_Data_Collection();
        $layout = $this->getLayout();

        foreach ($this->getChildren() as $child)
        {
            $pageType = $child->getPageType();

            $pageBlock = $layout->createBlock($pageType->getBlock())
                ->setTemplate($pageType->getTemplate())
                ->addData($child->getData())
                ->setPage($child);

            $sectionBlocks->addItem($pageBlock);
        }

        return $sectionBlocks;
    }

}
