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

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

class SimplelistsModelCategories extends YireoModel
{
    /**
     * Parent category ID
     *
     * @private int
     */
    private $_parent_id = null;

    /**
     * Parent category object
     *
     * @private object
     */
    private $_parent = null;

    /**
     * Constructor
     *
     * @access public
     * @param null
     * @return null
     */
    public function __construct()
    {
        $this->_search = array('title');
        $this->_debug = false;
        $this->_limit_query = false;
        $this->_orderby_default = 'lft,rgt';
        parent::__construct('category');
    }

    /**
     * Method to add extra data
     *
     * @access public
     * @param null
     * @return array
     */
    public function onDataLoad($item)
    {
        if (empty($item->active)) {
            $item->active = $this->getActive($item->id);
        }
        return $item;
    }

    /**
     * Method to get the number of active items
     *
     * @access public
     * @param 
     * @return JPagination
     */
    public function getActive( $catid = 0 )
    {
        $query = 'SELECT COUNT(s.id)'
            . ' FROM `#__simplelists_categories` AS s'
            . ' WHERE s.`category_id` = '. (int)$catid 
        ;
        $this->_db->setQuery( $query );
        $active = $this->_db->loadResult();
        return $active ;
    }

    /**
     * Method to build the database query
     *
     * @access protected
     * @param null
     * @return mixed
     */
    protected function buildQuery($query = '')
    {
        $query = "SELECT `category`.*, {access}, {editor}, `parent`.`title` AS `parent_title`, `parent`.`checked_out` AS `parent_checked_out`,\n"
            . " CASE WHEN CHAR_LENGTH(`category`.`alias`) THEN CONCAT_WS(':', `category`.`id`, `category`.`alias`) ELSE `category`.`id` END AS `slug`\n"
            . " FROM `#__categories` AS `category`\n"
            . " LEFT JOIN `#__categories` AS `parent` ON `parent`.`id` = `category`.`parent_id`\n"
        ;
        return parent::buildQuery($query);
    }

    /**
     * Method to build the query WHERE segment
     *
     * @access protected
     * @param null
     * @return string
     */
    protected function buildQueryWhere()
    {
        $this->addWhere('`category`.`extension`="com_simplelists"');

        if ($this->_parent_id > 0) {
            $this->addWhere('`category`.`parent_id`='.(int)$this->_parent_id);
        }

        return parent::buildQueryWhere();
    }

    /**
     * Method to build the query ORDER BY segment
     *
     * @access protected
     * @subpackage Yireo
     * @param null
     * @return string
     */
    protected function buildQueryOrderBy()
    {
        $default_ordering = 'lft, rgt';
        $orderby = $this->params->get('cat_orderby', $default_ordering);
        if ($orderby =='alpha') {
            $this->addOrderBy('`category`.`title` ASC');
        } elseif ($orderby =='ralpha') {
            $this->addOrderBy('`category`.`title` DESC');
        } else {
            if ($orderby == 'ordering') $orderby = $default_ordering;
            $this->addOrderBy('`category`.'.$orderby);
        }
        return parent::buildQueryOrderBy();
    }

    /**
     * Method to set the parent category
     *
     * @access public
     * @param integer
     * @return null
     */
    public function setParent($parent_id = null)
    {
        // Set the parent_id if given as argument
        if ($parent_id > 0) {
            $this->_parent_id = (int)$parent_id;
        }
    }

    /**
     * Method to get the fully loaded parent
     *
     * @access  private
     * @return  boolean True on success
     */
    public function getParent($parent_id = null)
    {
        // Set the parent_id if given as argument
        if ($parent_id > 0) {
            $this->_parent_id = (int)$parent_id;
        }

        // If the parent-object is empty, load it
        if (empty($this->_parent)) {
            require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/models/category.php';
            $model = new SimplelistsModelCategory();
            $model->setId($this->_parent_id);
            $this->_parent = $model->getData();
        }

        return $this->_parent;
    }
}
