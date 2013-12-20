<?php
class Evisu_Blog_Block_Archive extends Mage_Core_Block_Template
{
    private $year = null;
    private $month = null;
    private $yearCorrect = 0;
    private $_collectionSize;
    const DISPLAY_MONTH = 2;


    protected function _construct()
    {
        parent::_construct();
        $cacheId = md5('calendar' . $this->getRequest()->getParam('year') . $this->getRequest()->getParam('month'));
        $this->addData(array(
            'cache_lifetime'    => 3600 * 12,
            'cache_tags'        => array('blog_calendar'),
            'cache_key'         => 'CALENDAR_' . $cacheId,
        ));
    }

    /*public function getCacheKeyInfo()
    {
        $cacheId = md5('calendar' . $this->getRequest()->getParam('year') . $this->getRequest()->getParam('month'));
        return array('cache_id' => 'CALENDAR_'.$cacheId);
    }*/

    public function getArchiveCalendar()
    {
        $seconds_in_a_day = 60*60*24;
        for($month = 0; $month <= self::DISPLAY_MONTH - 1; $month++)
        {
            $start_day = mktime(0, 0, 0, $this->_getMonth() + $month, 1, $this->_getYear());
            $day = $start_day;

            $date_array = getdate($start_day);

            $calendar[$month]['caption'] = date('F y', $start_day);
            $year = $date_array['year'];
            $mon = $date_array['mon'];
            for($i = 0; $i < 6; $i++)
            {
                for ($j = 0; $j < 7; $j++)
                {
                    $current_day = getdate($day);
                    if ($current_day["mon"] != $date_array["mon"])
                    {
                        //$calendar[$i][$j]['date'] = "";
                        break;
                    }
                    else
                    {
                        if (($current_day["wday"] - 1 == $j && $current_day["wday"] != 0) || ($current_day["wday"] == 0 && $j == 6))
                        {
                            $calendar[$month][$i][$j]['date'] = $current_day["mday"];

                            $posts = $this->getArchivePostsCollection($year . '-' . $this->dateCorrector($mon) . '-' . $this->DateCorrector($current_day["mday"]));
                            if($posts->getSize() > 0)
                            {
                                //$calendar[$month][$i][$j]['thumbnail'] = $this->getCalendarPostImage($posts->getFirstItem());
                                $calendar[$month][$i][$j]['fulldate'] = $year . '-' . $this->dateCorrector($mon) . '-' . $this->dateCorrector($current_day["mday"]);
                                $calendar[$month][$i][$j]['url'] = $posts->getFirstItem()->getUrl();
                                $calendar[$month][$i][$j]['post_date'] = date('d F y', $day);
                                //$calendar[$month][$i][$j]['category'] = $posts->getFirstItem()->getParentCategory()->getName();
                                $calendar[$month][$i][$j]['caption'] = $posts->getFirstItem()->getPostTitle();
                                //$calendar[$i][$j]['data'] = $posts->getFirstItem()->getData();
                                //var_dump($posts->getFirstItem()->getParentCategory()->getName());
                            }

                            $day += $seconds_in_a_day;
                        }
                        else
                        {
                            $calendar[$month][$i][$j]['date'] = "";
                        }
                    }
                }
            }
        }
        //echo '<pre>';
        //var_export($calendar);
        return $calendar;
    }

    private function _getYear()
    {
        if(!$this->year)
        {
            if (!($year = Mage::app()->getRequest()->getParam('year')))
            {
                $year = date("Y");
            }
            $this->year = $year - $this->yearCorrect;
        }
        return $this->year;
    }

    private function _getMonth()
    {
        if(!$this->month)
        {
            if (!($month = Mage::app()->getRequest()->getParam('month')))
            {
                $month = date("m");
            }
            if($month > self::DISPLAY_MONTH - 1)
            {
                $month -= self::DISPLAY_MONTH - 1;
            }
            else
            {
                $month = 12 - (self::DISPLAY_MONTH - 1) + $month;
                $this->yearCorrent = 1;
            }
            $this->month = $month;
        }
        return $this->month;
    }

    public function getPreviousUrl()
    {
        if ($this->_getMonth() == 1)
        {
            $previousYear = $this->_getYear() - 1;
            $previousMonth = 12;
        }
        else
        {
            $previousYear = $this->_getYear();
            $previousMonth = $this->dateCorrector($this->_getMonth() - 1);
        }
        return Mage::getUrl('evisu_blog/archive/calendar', array('year' => $previousYear, 'month' => $previousMonth));
    }

    public function getNextUrl()
    {
        if (12 - $this->_getMonth() < (2 * self::DISPLAY_MONTH - 1))
        {
            $nextYear = $this->_getYear() + 1;
            $nextMonth = $this->dateCorrector($this->_getMonth() + (2 * self::DISPLAY_MONTH -1) - 12);
        }
        else
        {
            $nextYear = $this->_getYear();
            $nextMonth = $this->dateCorrector($this->_getMonth() + (2 * self::DISPLAY_MONTH -1));
        }
        return Mage::getUrl('evisu_blog/archive/calendar', array('year' => $nextYear, 'month' => $nextMonth));
    }

    public function showNextUrl()
    {
        if (($month = Mage::app()->getRequest()->getParam('month')) && ($year = Mage::app()->getRequest()->getParam('year')))
        {
            if($month !== date('m'))
            {
                return true;
            }
        }
        return false;
    }

    public function getCalendarPostImage(Fishpig_Wordpress_Model_Post_Abstract $post)
    {
        if (($image = $post->getFeaturedImage()) !== false) {
            return $image->getThumbnailImage();
        }
        return null;
    }

    public function getArchivePostsCollection($date, $postsLimit = 1, $page = 1)
    {
        $posts = Mage::getResourceModel('wordpress/post_collection')
            ->addIsPublishedFilter()
            ->addFieldToFilter('post_date', array("like" => '%'.$date.'%'))
            ->setOrderByPostDate()
            ->setPageSize($postsLimit)
            ->setCurPage($page);
        $this->_collectionSize = $posts->getSize();
        return $posts;
    }

    private function dateCorrector($val)
    {
        if (strlen($val) == 1)
        {
            return '0' . $val;
        }
        else
        {
            return $val;
        }
    }

}


