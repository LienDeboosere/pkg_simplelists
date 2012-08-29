<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo (info@yireo.com)
 * @package SimpleLists
 * @copyright Copyright 2011
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

// Require the parent view
require_once JPATH_COMPONENT.DS.'lib'.DS.'view.php';

/**
 * HTML View class 
 */
class SimpleListsViewHome extends YireoView
{
    /*
     * Display method
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        switch(JRequest::getVar('layout')) {
            case 'feeds':
                $feeds = $this->fetchFeeds('http://www.yireo.com/blog?format=feed&type=rss', 3);
                $this->assignRef('feeds', $feeds);
                break;
            case 'promotion':
                $html = YireoHelper::fetchRemote('http://www.yireo.com/advertizement.php', 'SimpleLists');
                print $html;
                exit;
        }

        parent::display($tpl);
    }

    /*
     * Display method
     *
     * @param string $url
     * @param int $max
     * @return array
     */
    public function fetchFeeds($url = '', $max = 3)
    {
        $rss = JFactory::getXMLParser('rss', array('rssUrl' => $url));
        if($rss == null) {
            return false;
        }
        $result = $rss->get_items();
        $feeds = array();
        $i = 0;
        foreach($result as $r) {
            if($i == $max) break;
            $feed = array();
            $feed['link'] = $r->get_link();
            $feed['title'] = $r->get_title();
            $feed['description'] = preg_replace( '/<img([^>]+)>/', '', $r->get_description());
            $feeds[] = $feed;
            $i++;
        }
        return $feeds;
    }
}
