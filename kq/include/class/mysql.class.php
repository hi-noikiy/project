<?php
#################################
#								#
#    最后更新日期2003-10-15		#
#    更新人：tianze98				#
#								#
#################################

class mysql {
/*网络配置*/
	var $Host     = "";
	var $Database = "";
	var $User     = "root";
	var $Password = "";
	
	var $Link_ID  = 0;
	var $Query_ID = 0;
	var $Record   = array();
	var $Row;
	
	var $Errno    = 0;
	var $Error    = "不能链接到数据库";
	
	var $Auto_free   = 0;
	var $Auto_commit = 0;
	
	var $jsonErr = false;
	
	function __construct($connentAry){
		  self::mysql($connentAry);
	}
	
	function mysql($connentAry){
		  $this->Host = $connentAry['host'];
		  $this->Database = $connentAry['database'];
		  $this->User = $connentAry['user'];
		  $this->Password = $connentAry['pass'];
	}
	
	function connect($autoDb=true) {
	    if ( 0 == $this->Link_ID ) {
	        $this->Link_ID=mysql_pconnect($this->Host, $this->User, $this->Password);
	        if (!$this->Link_ID) {
	            $this->halt("连接数据库失败，请检查您的配置文件");
	        }
	        if (!mysql_select_db($this->Database,$this->Link_ID)) {
	            if($autoDb){
	                if(!mysql_query('CREATE DATABASE '.$this->Database.';',$this->Link_ID)){
	                    $this->halt("创建数据库失败：".$this->Database);
	                }else{
	                	mysql_select_db($this->Database,$this->Link_ID);
	                }
	            }else $this->halt("找不到数据库：".$this->Database);
	        }
	    }
	}
	
	function getTableField($table){
		$this->connect();
		$fields = mysql_list_fields($this->Database,$table,$this->Link_ID);   
		$columns = mysql_num_fields($fields);   
		for($i=0;$i<$columns;$i++)   {
			$result[]=array('name'=>mysql_field_name($fields,$i),'type'=>mysql_field_type($fields,$i));
		}
		return $result;
	}
	
	function query($Query_String) {
	    $this->connect();
	    mysql_query("SET NAMES 'utf8'",$this->Link_ID);
	    $this->Query_ID = mysql_query($Query_String,$this->Link_ID);
	    $this->Row   = 0;
	    $this->Errno = mysql_errno();
	    $this->Error = mysql_error();
	    if (!$this->Query_ID) {
	        $this->halt("SQL语句执行失败: ".$Query_String);  //调试时
	        return false;
	    }else return $this->Query_ID;
	}
	
	function next() {
	    $this->Record = @mysql_fetch_array($this->Query_ID,MYSQL_ASSOC);
	    $this->Row   += 1;
	    $this->Errno = mysql_errno();
	    $this->Error = mysql_error();
            
	    $stat = is_array($this->Record);
	    if (!$stat && $this->Auto_free) {
	        mysql_free_result($this->Query_ID);
	        $this->Query_ID = 0;
	    }
	    return $stat;
	}
	
	function f($Name) {
	    return $this->Record[$Name];
	}
	
	function getList($SQL) {
		$this->query($SQL);
		$ary=array();
	    while($this->next()){
	        $ary[]=$this->Record;
	        $i++;
	    }
	    return $ary;
	}
	
	function getValue($SQL,$field=''){
	    $this->query($SQL);
            
	    if ($this->next())
	        if($field) return $this->f($field);
	            else return ($this->Record);
	    else
	        return false;
	}
	
	function insert($array,$table){
	    foreach($array as $key => $val){
	        $ary[]=$key."='".$val."'";
	    }
	    $sql="insert into ".$table." set ".implode(',',$ary);
	    $this->query($sql);
	    return $this->insert_id();
	}
	
	function insert_id() {
	    return mysql_insert_id($this->Link_ID);
	}
	
	function update($array,$table,$where){
	    foreach($array as $key => $val){
	        $ary[]=$key."='".$val."'";
	    }
	    if(is_array($ary) && sizeof($ary)>0){
		    $sql="update ".$table." set ".implode(',',$ary)." ".$where;
		    return $this->query($sql);
	    }else return false;
	}
	
	function result_num(){
		return mysql_num_rows($this->Query_ID);
	}
	
	function table_exists($tab){
		$sql="SHOW TABLES LIKE '%".$tab."%'";
		$this->query($sql);
		if($this->result_num()) return true;
			else return false;
	}
	
	function halt($msg) {
//		die('数据错误');
		if($this->jsonErr){
			disError($msg);
		}else{
		    printf("</td></tr></table><font color=#000000><b>Database error:</b> %s<br>\n", $msg);
		    printf("<b>MySQL Error</b>: %s (%s)<br></font>\n",$this->Errno,$this->Error);
		    die("Session halted.");
		}
	    throw new Exception($error);
	}
	
	function free_result() {
	    return mysql_free_result($this->Query_ID);
	}
	
	function disconnect() {
	    return mysql_close($this->Link_ID);
	}
}
?>