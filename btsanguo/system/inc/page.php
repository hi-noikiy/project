<?
//分页类
class page{
	private $url;
	private $pagesize;
	private $sql;
	private $getpage;

	//参数:sql语句，每页记录数，传递链接(例如"index.php?"或者"index.php?sort=1&"等)
    function __construct($sql,$pagesize,$url) {
		$this->url      = $url;
	    $this->pagesize = $pagesize;
		$this->sql      = $sql;
		$this->getpage  = mysql_escape_string($_REQUEST["page"]);
		if(!$this->getpage) {
			$this->getpage=1;
		}
    }
	//取记录总数
    function getCount() {
		return mysql_num_rows(mysql_query($this->sql));
    }
	//格式化sql语句
	function PageSql() {
		$nowpage    = $this->getpage;
		$limitNumber= $this->pagesize;
		if($nowpage<1) {
			$nowpage=1;
		}
		return $this->sql." limit ".($nowpage-1)*$limitNumber.",".$limitNumber;
		//return $this->sql." limit 0,".$limitNumber;
	}

	 //显示分页,参数:显示数字链接个数，开始步进的页
    function show($page = 8,$offset = 2){
		$mpurl     = $this->url;
		$curr_page = $this->getpage;
		$perpage   = $this->pagesize;
		$num=$this->getCount();//总记录数
        $multipage = "";
        //if ($num > $perpage){
            $pages = ceil($num / $perpage);
            $from = $curr_page - $offset;
            $to = $curr_page + $page - $offset - 1;
            if ($page > $pages){
                $from = 1;
                $to = $pages;
            }else{
                if ($from < 1){
                     $to = $curr_page + 1-$from;
                     $from = 1;
                     if (($to - $from) < $page && ($to - $from) < $pages){
                         $to = $page;
                     }
                }elseif ($to > $pages){
                     $from = $curr_page - $pages + $to;
                     $to = $pages;
                     if (($to - $from) < $page && ($to - $from) < $pages){
                           $from = $pages - $page + 1;
                     }
                }
            }
            $multipage .= "共<font color=red><b>".$num."</b></font>条\n";
            $multipage .= "，第<font color=red><b>".$curr_page."</b></font><b>/".$pages."</b>页\n";
            if ($curr_page - $offset > 1){
                 $multipage .= "<a href=\"".$mpurl."page=1\">首页</a>\n";
            }
			if ($num > $perpage){
				for ($i = $from; $i <= $to; $i++){
					if ($i != $curr_page){
						 $multipage .= "<a href=\"".$mpurl."page=".$i."\">[".$i."]</a>\n";
					}else{
						 $multipage .= "<font color=red><b>".$i."</b></font>\n";
					}
            }
            }
			if ($curr_page + $offset < $pages){
                  $multipage .= "<a href=\"".$mpurl."page=$pages\">尾页</a>\n";
            }
         return $multipage;
    }
}
//WAP分页类
class WapPage{
	private $url;
	private $pagesize;
	private $sql;
	private $getpage;

	//参数:sql语句，每页记录数，传递链接(例如"index.php?"或者"index.php?sort=1&"等)
    function __construct($sql,$pagesize,$url) {
		$this->url      = $url;
	    $this->pagesize = $pagesize;
		$this->sql      = $sql;
		$this->getpage  = mysql_escape_string($_REQUEST["page"]);
		if(!$this->getpage) {
			$this->getpage=1;
		}
    }
	//取记录总数
    function getCount() {
		return mysql_num_rows(mysql_query($this->sql));
    }
	//格式化sql语句
	function pageSql() {
		$nowpage    = $this->getpage;
		$limitNumber= $this->pagesize;
		if($nowpage<1) {
			$nowpage=1;
		}
		return $this->sql." limit ".($nowpage-1)*$limitNumber.",".$limitNumber;
		//return $this->sql." limit 0,".$limitNumber;
	}

	 //显示分页,参数:显示数字链接个数，开始步进的页
    function show($page = 5,$offset = 2){
		$mpurl     = $this->url;
		$curr_page = $this->getpage;
		$perpage   = $this->pagesize;
		$num=$this->getCount();//总记录数
        $multipage = "";
        //if ($num > $perpage){
            $pages = ceil($num / $perpage);
            $from = $curr_page - $offset;
            $to = $curr_page + $page - $offset - 1;
            if ($page > $pages){
                $from = 1;
                $to = $pages;
            }else{
                if ($from < 1){
                     $to = $curr_page + 1-$from;
                     $from = 1;
                     if (($to - $from) < $page && ($to - $from) < $pages){
                         $to = $page;
                     }
                }elseif ($to > $pages){
                     $from = $curr_page - $pages + $to;
                     $to = $pages;
                     if (($to - $from) < $page && ($to - $from) < $pages){
                           $from = $pages - $page + 1;
                     }
                }
            }
            if ($curr_page - $offset > 1){
                 $multipage .= "<a href=\"".$mpurl."page=1\">首页</a>\n";
            }
			if ($num > $perpage){
				for ($i = $from; $i <= $to; $i++){
					if ($i != $curr_page){
						 $multipage .= "<a href=\"".$mpurl."page=".$i.$ParaURL."\">[".$i."]</a>\n";
					}else{
						 $multipage .= "".$i."\n";
					}
            }
            }
			if ($curr_page + $offset < $pages){
                  $multipage .= "<a href=\"".$mpurl."page=$pages\">尾页</a>\n";
            }
            $multipage .= "共".$num."条\n";
            //$multipage .= "，第".$curr_page."/".$pages."页\n";
		 return $multipage;
    }
}

?>