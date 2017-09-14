<?php
// +----------------------------------------------------------------------
// | 无限级树操作类
// +----------------------------------------------------------------------
// | 中瑞卷管理系统
// +----------------------------------------------------------------------
// | Author: 嗄沬 280708784@qq.com
// +----------------------------------------------------------------------

namespace Common\Controller;

class Tree{

    //默认配置
    protected $_config = array(
        'FIELDS'            => array('mid','pid','menuName','url','ico', 'isshow', 'position'),
		'IMGPATH'           => '/ico',
		'ROOTID'            => 0
    );
	protected $data       = array();
	protected $pdata      = array();
	protected $indentArr  = array();
	protected $root_id    = 0;
	protected $layer      = 0;
	
	public $menuStr    = null;
	public $menuStrCheck    = null;
	public $menuArray    = null;

    public function __construct($config='') {
       if(is_array($config))
	   {
			foreach($config as $k=>$v)
			{
				$this->_config[$k] = $v;
			}
	   }
    }
	public function setData($data)
	{		
		$fields = $this->_config['FIELDS'];

		if(!is_array($data)) return;

		if (!$Auth) {
           		$Auth     =   new \Common\Controller\Auth();
       	}

		foreach($data as $k => $a)
		{

			if($a['pid'] == 0 && !is_numeric($pid_temp[$a['mid']])){
				$pid_temp[$a['mid']] = '';
			}
			if ($Auth->check($a['url'],CPUID,$type,$mode) || $a['pid'] == 0 || IS_ROOT){
				$this->data[$a[$fields[1]]][] = array($a[$fields[0]],$a[$fields[2]],$a[$fields[3]],$a[$fields[4]], $a[$fields[6]]);
				// var_dump($this->data);
				if($a['pid'] != 0){
					$pid_temp[$a['pid']] = $k;
				}
				
			}
		}
		if(!IS_ROOT){
			$i = 0;
		
			foreach ($pid_temp as $key => $value) {
				$temp_data[$this->data[0][$i][0]]=$i;
				$i++;
			}

			foreach ($pid_temp as $key => $value) {
				if(empty($value)){
					unset($this->data[0][$temp_data[$key]]);
				}
			}
		}
		foreach($this->data as $k=>$a)
		{
			$this->pdata[] = $k;
		}
	}
	public function chrent_menu($pid=0)
	{
		if(is_array($this->data[$pid]))
		{
			foreach($this->data[$pid] as $k=>$a)
			{

				if($a[4] == 1){


					$this->menuStr .= '<div class="firstMenus">';
					$this->menuStr .= '<h4 mid="' . $a[0] . '">';
					$this->menuStr .= $a[1] . '</h4><em onclick="delMenu(' . $a[0] . ')"></em></div>';



				}elseif($a[4] == 2){
					$this->menuStr .= '<div>';
					$this->menuStr .= '<div class="inputText secondMenus">';
					$this->menuStr .= '<h4 mid="' . $a[0] . '">';
					$this->menuStr .= $a[1] . '<em onclick="delMenu(' . $a[0] . ')"></em></h4>';
					$this->menuStr .= '<ul title="' . $a[1] . '">';
				}else{
					$this->menuStr .= '<li mid="' . $a[0] . '" id="m' . $a[0] . '" title="' . U($a[2]) . '" class="left_menu_link"><span>' . $a[1] . '</span><em onclick="delMenu(' . $a[0] . ')"></em></li>';
				}
				if(in_array($a[0],$this->pdata))
				{
					$this->chrent_menu($a[0]);
				}
				if($a[4] == 2)
				{
					$this->menuStr .= '</ul></div></div>';
				}
			}
		}
	}


	public function getMenuArray($pid=0)
	{
		if(is_array($this->data[$pid])){
			// print_r($this->data[$pid]);
			foreach($this->data[$pid] as $k=>$a){

					$this->menuArray[$pid][] = $a;
					if(in_array($a[0],$this->pdata)){
						$this->getMenuArray($a[0]);
				}
			}
		}
	}

	public function checkMenu($pid=0, $checkRole = '')
	{
		if(is_array($this->data[$pid]))
		{
			foreach($this->data[$pid] as $k=>$a)
			{

				$checkStr = '';
				if(strstr(',' . $checkRole . ',', ',' . $a[0] . ',')){
					$checkStr = ' checked = "checked"';
				}
				if($a[4] == 1){
					$this->menuStrCheck .= '<div class="firstMenus">';
					$this->menuStrCheck .= '<h4>';
					$this->menuStrCheck .= $a[1] . '</h4></div>';
				}elseif($a[4] == 2){
					$this->menuStrCheck .= '<div>';
					$this->menuStrCheck .= '<div class="inputText secondMenus">';
					$this->menuStrCheck .= '<h4>';
					$this->menuStrCheck .= '<input name="data[rules][]" type="checkbox" id="cinema' . $a[0] . '" value="' . $a[0] . '" ' . $checkStr . '><label for="cinema' . $a[0] . '">' . $a[1] . '</label></h4>';
					$this->menuStrCheck .= '<ul title="' . $a[1] . '">';
				}else{
					
					$this->menuStrCheck .= '<li id="m' . $a[0] . '" title="' . U($a[2]) . '" class="left_menu_link"><input name="data[rules][]" type="checkbox" id="cinema' . $a[0] . '" value="' . $a[0] . '" ' . $checkStr . '><label for="cinema' . $a[0] . '">' . $a[1] . '</label></li>';
				}
				if(in_array($a[0],$this->pdata))
				{
					$this->checkMenu($a[0], $checkRole);
				}
				if($a[4] == 2)
				{
					$this->menuStrCheck .= '</ul></div></div>';
				}
			}
		}
	}

	//树形select显示
	public function option($pid=0,$selected='')
	{
		if(is_array($this->data[$pid]))
		{
			$end = end($this->data[$pid]);
			foreach($this->data[$pid] as $a)
			{
				// print_r($this->indentArr);
				$str = '';
				if(count($this->indentArr) == 1){
					$str = ' style="color:red"';
				}

				if(count($this->indentArr) == 2){
					$str = ' style="color:green"';
				}
				$this->menuStr .= "<option value=\"$a[0]\" $str";
				if($selected!='' && $selected==$a[0]) $this->menuStr .= " selected";
				$this->menuStr .= ">";
				$this->optionsindent($a[0],$pid);
				$this->menuStr .= $a[1];
				$this->menuStr .= "</option>";
				if(in_array($a[0],$this->pdata))
				{
					$this->option($a[0],$selected);
				}
				array_pop($this->indentArr);
			}
		}
	}
	public function optionsindent($id,$pid)
	{
		foreach($this->indentArr as $a)
		{
			$img = '┝';
			$this->menuStr .= $img;
		}
		$end = end($this->data[$pid]);
		$isend = $end[0]==$id?1:0;
		array_push($this->indentArr,$isend);

	}
}
