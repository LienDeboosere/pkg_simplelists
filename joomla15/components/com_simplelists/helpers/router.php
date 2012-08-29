<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the Yireo Helper
if(class_exists('YireoHelper') == false) {
    require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_simplelists'.DS.'lib'.DS.'helper.php';
}

/**
 * Router Helper
 */
class SimplelistsHelperRouter
{
    public function getMenuItems()
    {
        static $items = null;
        if(empty($items)) {
            $component = &JComponentHelper::getComponent('com_simplelists');
            $menu = &JSite::getMenu();
        
            if(YireoHelper::isJoomla15()) {
                $items = $menu->getItems('componentid', $component->id);
            } else {
                $items = $menu->getItems('component_id', $component->id);
            }
        }
        return $items;
    }
}
