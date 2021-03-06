<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2014
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* Item Table class
*/
class TableItem extends YireoTable
{
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    public function __construct(& $db) 
    {
        // @todo: Extra field "approved"
        // Initialize the fields
        $this->_fields = array(
            'id' => null,
            'title' => null,
            'alias' => null,
            'link' => null,
            'link_type' => null,
            'text' => null,
            'picture' => null,
            'hits' => null,
        );

        parent::__construct('#__simplelists_items', 'id', $db);
    }
}
