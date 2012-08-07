<?php
/**
 * @file
 * @package xcforum
 * @version $Id$
 **/

if(!defined('XOOPS_ROOT_PATH'))
{
	exit;
}

/**
 * Xcforum_CriteriaObject
 **/
class Xcforum_CriteriaObject extends Legacy_AbstractObject
//class Xcforum_CriteriaObject extends XoopsSimpleObject
{

	/**
	 * Return HTML string for displaying only by HTML.
	 * The second parametor doesn't exist.
	 */
	public function getShow( $key, $obj=NULL )
	{
//function &displayTarea( $text , $html = 0 , $smiley = 1 , $xcode = 1 , $image = 1 , $br = 1 , $nbsp = 0 , $number_entity = 0 , $special_entity = 0 )

		$value = null;
		$vars = $this->mVars[$key];

		switch ($vars['data_type']) {
			case XOBJ_DTYPE_BOOL:
			case XOBJ_DTYPE_INT:
			case XOBJ_DTYPE_FLOAT:
				$value = $vars['value'];
				break;

			case XOBJ_DTYPE_STRING:
				$root =& XCube_Root::getSingleton();
				$root->setTextFilter( Xcforum_TextSanitizer::getInstance() );     // added
				$textFilter =& $root->getTextFilter();
				$value = $textFilter->toShow($vars['value']);
				break;

			case XOBJ_DTYPE_TEXT:
				if (is_object($obj) && ($obj instanceof Xcforum_PostsObject) ){
					$html = $obj->getShow('html');
					$smiley = $obj->getShow('smiley');
					$xcode = $obj->getShow('xcode');
					$image = 1;
					$br = $obj->getShow('br');
					$nbsp = $obj->getShow('nbsp');
					$number_entity = $obj->getShow('number_entity');
					$special_entity = $obj->getShow('special_entity');
				} else {
					$html=$number_entity=$special_entity=0; $smiley=$xcode=$image=$br=$nbsp=1;
				}

				$root =& XCube_Root::getSingleton();
				$root->setTextFilter( Xcforum_TextSanitizer::getInstance() );     // added
				$textFilter =& $root->getTextFilter();
				//$value = $textFilter->toShowTarea($vars['value'], 0, 1, 1, 1, 1);
				$value = $textFilter->toShowTarea($vars['value'], $html, $smiley, $xcode, $image, $br, $nbsp, $number_entity, $special_entity );
				//$value = $textFilter->displayTarea($vars['value'], $html, $smiley, $xcode, $image, $br, $nbsp, $number_entity, $special_entity );
				break;
		}

		return $value;
	}

	/** abstract
	 * initAdditionalFields
	 **/
	public function initAdditionalFields()
	{
	}

}

/**
 * Xcforum_CriteriaHandler
 **/
class Xcforum_CriteriaHandler extends Legacy_AbstractClientObjectHandler
{

	private $_join;
	private $_main_alias;
	private $_custom_sql;

	public function setJoin( $_join ){
		$this->_join = $_join;
	}

	public function setMainAlias( $_main_alias ){
		$this->_main_alias = $_main_alias;
	}

	/**
	 * Return array of object with $criteria.
	 *
	 * @access public
	 * @param CriteriaElement $criteria
	 * @param int  $limit
	 * @param int  $start
	 * @param bool $id_as_key
	 * @param string $custom_sql
	 * @param int $add_init
	 *
	 * @return array
	 */
	public function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false, $custom_sql = null, $add_init = 0 )
	{
		$ret = array();

		//$_main_alias = $this->_main_alias ? $this->_main_alias.'.' : '';
		//$_main_alias = $this->_main_alias ? : '';
		$_main_alias = '';

		if ( !$custom_sql ){
			return parent::getObjects($criteria, $limit, $start, $id_as_key);
		} else {
			$sql = $custom_sql;
			if ($limit === null) {
				$limit = $criteria->getLimit();
			}

			if ($start === null) {
				$start = $criteria->getStart();
			}

			$sorts = array();
			foreach ($criteria->getSorts() as $sort) {
				//$sorts[] = '`' . $_main_alias . $sort['sort'] . '` ' . $sort['order'];
				//$sorts[] = $_main_alias . $sort['sort'] . ' ' . $sort['order'];
				$sorts[] = '`' . $sort['sort'] . '` ' . $sort['order'];
			}

			if ($criteria->getSort() != '') {
				$sql .= " ORDER BY " . implode(',', $sorts);
			}

			$result = $this->db->query($sql, $limit, $start);

			if (!$result) {
				return $ret;
			}

			while($row = $this->db->fetchArray($result)) {
				$obj =new $this->mClass();
				if ($add_init>=1){   // added
					$obj->initAdditionalFields( $add_init );
				}
				$obj->mDirname = $this->getDirname();
				$obj->assignVars($row);
				$obj->unsetNew();

				if ($id_as_key)	{
					$ret[$obj->get($this->mPrimary)] =& $obj;
				}
				else {
					$ret[]=&$obj;
				}

				unset($obj);
			}

			return $ret;
		}

	}

	function getCount($criteria = null, $custom_sql = null )
	{
		if ( !$custom_sql ){
			return parent::getCount($criteria);
		} else {
			$sql = "SELECT COUNT(*) c ".$custom_sql;
			return $this->_getCount($sql);		}
	}

	/*
	 * deleteAll
	 * no key table cannot use original deleteAll method,
	 * this is overrides one
	 */
	public function deleteAll($criteria, $force = false)
	{
		$sql = "DELETE FROM `" . $this->mTable . '`';
		if (is_a($criteria, 'CriteriaElement')) {
			$where = $this->_makeCriteria4sql($criteria);
			if ($where) {
				$sql .= " WHERE " . $where;
			}
		}
		return $force ? $this->db->queryF($sql) : $this->db->query($sql);
	}

	public function getJoinHandler($table_name, $main_field, $sub_field, $join_type='LEFT', $table_alias=""){
		return new Xcforum_JoinCriteriaHandler($table_name, $main_field, $sub_field, $join_type, $table_alias);
	}

}

/**
 * Xcforum_JoinCriteriaHandler
 **/
class Xcforum_JoinCriteriaHandler
{
	private $_table_name;
	private $_main_field;
	private $_sub_field;
	private $_join_type;
	private $_next_join;
	private $_table_alias; // thanks towdash

	public function __construct($table_name, $main_field, $sub_field, $join_type='LEFT', $table_alias="")
	{
		$this->_table_name = $table_name;
		$this->_main_field = $main_field;
		$this->_sub_field = $sub_field;
		$this->_join_type = $join_type;
		$this->_next_join = false;
		$this->_table_alias = $table_alias;
	}

	public function cascade(&$joinCriteria)
	{
		$this->_next_join =& $joinCriteria;
	}

	public function render($main_table)
	{
		if($this->_table_alias == ""){
			$table_alias = $this->_table_name;
			$alias_def = "";
		} else {
			$table_alias = $this->_table_alias;
			$alias_def = " AS ".$table_alias;
		}
		$join_str = " ".$main_table." ".$this->_join_type." JOIN ".$this->_table_name . $alias_def." ON ".$main_table.".".$this->_main_field."=".$table_alias.".".$this->_sub_field." ";
		if ($this->_next_join) {
			$join_str .= $this->_next_join->render($table_alias);
		}
		return $join_str;
	}

	public function getMainAlias() {
		return $this->_main_alias;
	}

}


?>
