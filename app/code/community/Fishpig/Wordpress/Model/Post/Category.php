<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_Model_Post_Category extends Fishpig_Wordpress_Model_Term
{

    private $_isActive = null;

	public function _construct()
	{
		$this->_init('wordpress/post_category');
	}

	/**
	 * Retrieve the taxonomy type
	 *
	 * @return string
	 */
	public function getTaxonomy()
	{
		return 'category';
	}
	
	/**
	 * Returns the amount of posts related to this object
	 *
	 * @return int
	 */
    public function getPostCount()
    {
    	return $this->getItemCount();
    }

	/**
	 * Retrieve a collection of children terms
	 *
	 * @return Fishpig_Wordpress_Model_Mysql_Term_Collection
	 */
	public function getChildrenCategories()
	{
		return $this->getChildrenTerms();
	}
	
	/**
	 * Retrieve the parent category
	 *
	 * @return Fishpig_Wordpress_Model_Resource_Post_Category
	 */
	public function getParentCategory()
	{
		return $this->getParentTerm();
	}

	/**
	 * Retrieve the string that is prefixed to all category URI's
	 *
	 * @return string
	 */
	public function getUriPrefix()
	{
		$helper = Mage::helper('wordpress');

		if ($helper->isPluginEnabled('No Category Base WPML') || $helper->isPluginEnabled('No Category Base')) {
			return '';
		}

		return ($base = trim($helper->getWpOption('category_base', 'category'), '/ ')) === ''
			? $this->getTaxonomyType()
			: $base;
	}

    /**
     * Retrieve the URL for this term
     *
     * @return string
     */
    public function getUrl()
    {

        if (!$this->hasUrl()) {
            $helper = Mage::helper('core/url');
            $param = 'cat['.$this->getId().']';
            $url = $helper->removeRequestParam($helper->getCurrentUrl(),$param);
            if(!$this->isActive())
            {
                $url =  $helper->addRequestParam($url, array($param => 1));
            }

            $this->setUrl($url);
        }

        return $this->_getData('url');
    }

    //filter by current category is active
    public function isActive()
    {
        if(is_null($this->_isActive))
        {
            $filter = Mage::app()->getRequest()->getParam('cat');
            if(!empty($filter[$this->getId()]))
            {
                $this->_isActive = true;
            }
            else
            {
                $this->_isActive = false;
            }
        }
        return $this->_isActive;
    }

}
