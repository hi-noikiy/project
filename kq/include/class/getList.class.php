<?php
class getList { // 据列表获取类---基础类
	public $tableName = ""; // 取的表名称
	public $exTableName = "";
	public $fieldList = "*"; // 列表时的字段
	public $fields = "*"; // 单条数据时的字段
	public $wheres = ""; // 件
	public $groupby = ""; // roup by
	public $orders = ""; // 序
	public $key = 'id';
	public $getNo = null; // 定获取第几条的数据记录,该属性优先于getPageNo
	public $pageReNum = 20; // 定每页的记录数量
	public $p = 1; // getPageNo,指定获取第几页的数据记录,如果要使用该属性，需将getNo置null
	protected $getPageNo = 1; // 定获取第几页的数据记录,如果要使用该属性，需将getNo置null
	public $recordCount = 0; // 回总共有多少条数据记录
	public $pageCount = 0; // 回总共有多少页
	protected $isgetRC = true; // 否计算条数
	protected $countSql = ""; // 于计算记录总条数的SQL语句
	protected $eventFuncName = ""; // 处理每行数据的函数名
	public $debug = false; // 否调试状态
	public $querySql = '';
	public $errMsg = '';
	public $permCheck = true;
	function __construct() {
	}
	public function getTableField($blob = false) {
		global $webdb;
		$result = $webdb->getTableField ( $this->tableName );
		if (! $blob) {
			foreach ( $result as $k => $v ) {
				if ($v ['type'] == 'blob')
					$result [$k] ['type'] = 'string';
				if ($v ['type'] == 'real')
					$result [$k] ['type'] = 'string';
				if ($v ['type'] == 'datetime')
					$result [$k] ['type'] = 'string';
			}
		}
		return $result;
	}
	public function setOrder($order) {
		$this->orders = $order;
	}
	public function setWhere($where) {
		if ($where)
			if ($this->wheres)
				$this->wheres .= " and " . $where . " ";
			else
				$this->wheres = $where;
	}
	public function setLimit($s, $l) {
		$this->getNo = $s;
		$this->pageReNum = $l;
	}
	public function setField($field, $add = true) {
		if (is_array ( $field ))
			$fieldStr = implode ( ',', $field );
		else
			$fieldStr = $field;
		if ($this->fieldList && $add)
			$this->fieldList .= ',' . $fieldStr;
		else
			$this->fieldList = $fieldStr;
		if ($this->fields && $add)
			$this->fields .= ',' . $fieldStr;
		else
			$this->fields = $fieldStr;
	}
	
	/**
	 * 初始化完相关参数后，获取数据
	 */
	public function getArray($tag = '') {
		global $webdb;
		if ($tag != 'pass') {
			if ($this->tableName && $this->permCheck && ! permission::check ( $this->tableName, 's_tag' )) {
				permission::errMsg ();
				return false;
			}
		}
		
		if ($this->fieldList == "") {
			$this->fieldList = "*";
		}
		if ($this->wheres != "") {
			$this->wheres = " where " . $this->wheres;
		}
		if ($this->groupby != "") {
			$this->groupby = " group by " . $this->groupby;
		}
		if ($this->orders != "") {
			$this->orders = " order by " . $this->orders;
		}
		if ($this->exTableName) {
			$this->tableName .= ',' . $this->exTableName;
		}
		
		if ($this->isgetRC) {
			if ($this->recordCount <= 0) {
				if ($this->countSql == "") {
					if ($this->groupby) {
						$sql = "select count(DISTINCT(" . str_replace ( 'group by ', '', $this->groupby ) . ")) as 'rno' from " . $this->tableName . " " . $this->wheres;
						$webdb->query ( $sql );
						$webdb->next ();
					} else {
						$sql = "select count(*) as 'rno' from " . $this->tableName . " " . $this->wheres;
						$webdb->query ( $sql );
						$webdb->next ();
					}
				} else {
					$webdb->query ( $this->countSql );
					$webdb->next ();
				}
				$this->recordCount = $webdb->f ( "rno" );
			}
			$this->pageCount = ceil ( $this->recordCount / $this->pageReNum );
		}
		if ($this->getNo === null) {
			if ($this->p)
				$this->getPageNo = $this->p;
			$firstno = $this->pageReNum * ($this->getPageNo - 1);
			if ($firstno < 0)
				$firstno = 0;
		} else
			$firstno = $this->getNo;
		$sql = "select " . $this->fieldList . " from " . $this->tableName . " " . $this->wheres . " " . $this->groupby . " " . $this->orders . " limit " . $firstno . "," . $this->pageReNum;
		if ($this->debug || $_GET ['debug'])
			echo $sql;
		$this->querySql = $sql;
		$result = $webdb->getList ( $sql );
		if ($this->eventFuncName) {
			if (is_array ( $result )) {
				foreach ( $result as $k => $v ) {
					$result [$k] = call_user_func ( $this->eventFuncName, $v );
				}
			}
		}
		if (! $result)
			$result = array ();
		return $result;
	}
	public function getRowArray($id, $field = '') {
		global $webdb;
		
		if ($this->fields == "") {
			$this->fields = "*";
		}
		$sql = 'select ' . $this->fields . ' from ' . $this->tableName . ' where ' . $this->key . '=' . string::qs ( $id );
		if ($this->wheres) {
			$sql .= ' and ' . $this->wheres;
		}
		
		$webdb->query ( $sql );
		if ($webdb->next ()) {
			$result = $webdb->Record;
			if (! $field)
				return $result;
			else
				return $result [$field];
		} else {
			return false;
		}
	}
	function getInfo($id, $field = null, $tag = '') {
		if (! $id)
			return false;
		global $webdb;
		// spark s_tag=>e_tag
		if ($tag != 'pass') {
			if ($this->tableName && $this->permCheck && ! permission::check ( $this->tableName, 'e_tag' )) {
				permission::errMsg ();
				return false;
			}
		}
		
		! $field && $field = '*';
		$sql = "select " . $field . " from " . $this->tableName . " where " . $this->key . "='" . $id . "';";
		$result = $webdb->getValue ( $sql );
		if ($this->eventFuncName) {
			$result = call_user_func ( $this->eventFuncName, $result );
		}
		if ($field != '*')
			return $result [$field];
		else
			return $result;
	}
	function addData($array) {
		global $webdb;
		unset ( $array [$this->key] );
		// foreach($array as $k=>$v){
		// $sql="ALTER TABLE ".$this->tableName." ADD ".$k." varchar(130) NULL DEFAULT null;";
		// $webdb->query($sql);
		// }
		if ($this->tableName && $this->permCheck && ! permission::check ( $this->tableName, 'a_tag' )) {
			permission::errMsg ();
			return false;
		}
		
		$field_ary = $this->getTableField ();
		foreach ( $field_ary as $field ) {
			$fields [] = $field ['name'];
		}
		foreach ( $array as $k => $v ) {
			if (! in_array ( $k, $fields ))
				unset ( $array [$k] );
		}
		return $webdb->insert ( $array, $this->tableName );
	}
	function editData($array, $id, $tag = '') {
		global $webdb;
		unset ( $array [$this->key] );
		if ($tag != 'pass') {
			// echo $this->tableName;exit;
			if ($this->tableName && $this->permCheck && ! permission::check ( $this->tableName, 'e_tag' )) {
				permission::errMsg ();
				return false;
			}
		}
		
		$field_ary = $this->getTableField ();
		foreach ( $field_ary as $field ) {
			$fields [] = $field ['name'];
		}
		foreach ( $array as $k => $v ) {
			if (! in_array ( $k, $fields ))
				unset ( $array [$k] );
		}
		return $webdb->update ( $array, $this->tableName, "where " . $this->key . "='" . $id . "'" );
	}
	function delete($id) {
		global $webdb;
		if ($this->tableName && $this->permCheck && ! permission::check ( $this->tableName, 'd_tag' )) {
			permission::errMsg ();
			return false;
		}
		return $webdb->query ( "delete from " . $this->tableName . " where " . $this->key . "='" . $id . "'" );
	}
	public function getPageInfoHTML($page = 0, $url = '') {
		if (! $url) {
			$html = true;
			$url = '?' . $this->urlkill ( 'p', false ) . '&p=';
		}
		if ($page) {
			if ($page == 1)
				$result = '共有<strong>' . $this->recordCount . '</strong>条记录，分<strong>' . $this->pageCount . '</strong>页显示';
			else
				$result = '当前第 <input name="goPageNo" value="' . $this->getPageNo . '" size="2"><input type="button" value="GO" onclick="window.location.href=\'' . $url . '\'+$(\'goPageNo\').value"> 页';
		} else {
			$htmlstr = '%pagestr%';
			$fstr = '<a href="%url%" class="BtnFirst">首页</a>';
			$pstr = '<a href="%url%" class="BtnPrev">上一页</a>';
			$nstr = '<a href="%url%" class="BtnNext">下一页</a>';
			$estr = '<a href="%url%" class="BtnEnd">尾页</a>';
			$goto = '<a href="%url%" class="BtnNum">%num%</a>';
			$now = '<em class="BtnNumSelect">%num%</em>';
			
			if ($this->getPageNo > 1) {
				$fstr = str_replace ( '%url%', $url . '1', $fstr );
				$pstr = str_replace ( '%url%', $url . ($this->getPageNo - 1), $pstr );
			} else {
				$fstr = str_replace ( '%url%', 'javascript:;', $fstr );
				$pstr = str_replace ( '%url%', 'javascript:;', $pstr );
			}
			
			if ($this->getPageNo != $this->pageCount && $this->pageCount > 0) {
				$nstr = str_replace ( '%url%', $url . ($this->getPageNo + 1), $nstr );
				$estr = str_replace ( '%url%', $url . ($this->pageCount), $estr );
			} else {
				$nstr = str_replace ( '%url%', 'javascript:;', $nstr );
				$estr = str_replace ( '%url%', 'javascript:;', $estr );
			}
			
			$begin = (($this->getPageNo - 4) > 0) ? $this->getPageNo - 4 : 0;
			$end = (($this->getPageNo + 3) < $this->pageCount) ? $this->getPageNo + 3 : $this->pageCount;
			$numstr = '';
			for($i = $begin; $i < $end; $i ++) {
				if ($this->getPageNo == $i + 1) {
					$tstr = str_replace ( '%num%', ($i + 1), $now );
				} else {
					$tstr = str_replace ( '%url%', $url . ($i + 1), $goto );
					$tstr = str_replace ( '%num%', ($i + 1), $tstr );
				}
				$numstr .= $tstr;
			}
			
			$pagestr = $fstr . $pstr . $numstr . $nstr . $estr;
			
			$pagehtml = str_replace ( '%pagestr%', $pagestr, $htmlstr );
		}
		return $pagehtml;
	}
	function urlkill($key, $fullurl = true) { // 多个key可以通过|分隔
		$url = preg_replace ( '/&(' . $key . ')\=[^&]*/', '', '&' . $_SERVER ['QUERY_STRING'] );
		if ($fullurl)
			$url = $_SERVER ['SCRIPT_NAME'] . '?' . substr ( $url, 1 );
		else
			$url = substr ( $url . $ext, 1 );
		return $url;
	}
	/*
	 * 为了兼容子类
	 */
	function getList() {
		return $this->getArray ();
	}
	function add($array) {
		return $this->addData ( $array );
	}
	function edit($array, $id) {
		return $this->editData ( $array, $id );
	}
	function del($id) {
		return $this->delete ( $id );
	}
	function setKw($ary) {
		return false;
	}
	/*
	 * 为了加载类
	 */
	function none() {
	}
}
?>