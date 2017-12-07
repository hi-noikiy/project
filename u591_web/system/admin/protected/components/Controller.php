<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController{
	public $uploadPath;
	public $field;
	public $condition;
	public $params;
	public $order;
	public $bui;
	public $mangerInfo;
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	//获取session
	public function getSession($val){
		return Yii::app()->session["$val"];
	}
	
	public function actionIndex(){	
		$_model=ucfirst($this->getId());
		$info =new $_model();
		$model=$info->model();
		$condition=$this->_search();
		$goodInfo= $this->_list($model,$condition);
		
		$this->render('index',$goodInfo);
	}
	
	protected function download($info, $suffix = '.txt'){
		set_time_limit(0);
		$filename = 'code_'.date('YmdHi').$suffix;
		if($suffix == '.txt'){
			header("Content-type: application/octet-stream");
			header('Content-Disposition: attachment; filename="'.$filename.'"'); //指定下载文件的描述
			$str = '';
			foreach ($info as $v){
				$str .=$v. "\r\n";
			}
			echo $str;
			unset($str);
		} else if($suffix == '.xls'){
            header("Content-type:application/vnd.ms-excel");
			header('Content-Disposition:filename='.$filename);
			echo "<table><tr>";
			foreach ($info['tag'] as $v){
				echo "<td>$v</td>";
			}
			echo "</tr>";
			foreach ($info['data'] as $v){
				echo "<tr align='left'>";
				if(isset($v['A']))
					echo "<td style='vnd.ms-excel.numberformat:@'>{$v['A']}</td>";
				if(isset($v['B']))
					echo "<td style='vnd.ms-excel.numberformat:@'>{$v['B']}</td>";
				if(isset($v['C']))
					echo "<td style='vnd.ms-excel.numberformat:@'>{$v['C']}</td>";
				if(isset($v['D']))
					echo "<td style='vnd.ms-excel.numberformat:@'>{$v['D']}</td>";
				if(isset($v['E']))
					echo "<td style='vnd.ms-excel.numberformat:@'>{$v['E']}</td>";
				if(isset($v['F']))
					echo "<td style='vnd.ms-excel.numberformat:@'>{$v['F']}</td>";
                if(isset($v['G']))
                    echo "<td style='vnd.ms-excel.numberformat:@'>{$v['G']}</td>";
                if(isset($v['H']))
                    echo "<td style='vnd.ms-excel.numberformat:@'>{$v['H']}</td>";
                if(isset($v['I']))
                    echo "<td style='vnd.ms-excel.numberformat:@'>{$v['I']}</td>";
				echo "</tr>";
			}
			echo "</table>";
		}
        unset($info);
		exit();
	}
	
	public function _search(){
		if(method_exists($this, '_filter')){
			$this->_filter($this->condition);
		}
		if(method_exists($this, '_condition')){
			$this->_condition($this->condition);
		}
		return $this->condition;
	}
	
	function actionSearch(){
		$this->render('search');
	}
	
	public function _list($model,$condition){
		$criteria = new CDbCriteria;
		if(!empty($condition)){
			if(isset($condition['param'])){
				$param = $condition['param'];
				unset($condition['param']);
			}
			if(isset($condition['excelLoad'])){
			    $excel = $condition['excelLoad'];
                unset($condition['excelLoad']);
            }
            if(isset($condition['textLoad'])){
                $text = $condition['textLoad'];
                unset($condition['textLoad']);
            }
			$condition=implode($condition,' and ');
			$criteria->condition =$condition;
		}
		$count = $model->count($criteria); //统计

		//txt导出
		if(isset($_POST['textLoad'])){
			ini_set('memory_limit', '1024M');
			$info=$model->findAll($criteria);
            $newInfo = array();
            foreach ($info as $v){
                $field = $text['field'];
                $newInfo[] = $v->$field;
            }
			$this->download($newInfo);
		} else if(isset($_POST['excelLoad'])){
            $info=$model->findAll($criteria);
            $newInfo = array();
            foreach ($excel as $k => $v){
                $newInfo['tag'][] = $v['name'];
            }
            foreach ($info as $k => $v) {
                foreach ($excel as $k1 => $v1){
                    $field = $v1['field'];
                    $newInfo['data'][$k][$k1] = $v->$field;
                }
            }
            $this->download($newInfo, '.xls');
        }
		$pages=new CPagination($count);
		$pages->pageSize=20;
		$pages->applyLimit($criteria);
		 
		$criteria->limit=$pages->pageSize;
	
		if(method_exists($this, '_field')){
			$this->_field($this->field);
			$criteria->select = $this->field;
		}
		if(isset($_POST['pagination'])){
			$pagination=$_POST['pagination'];
			$criteria->offset=($_POST['pagination']-1)*$criteria->limit;
		}else {
			$pagination=1;
			$criteria->offset=0;
		}
		//排序
		if(isset($_POST['_order']) && isset($_POST['_sort'])){
			$order=$_POST['_order'];
			$sort=$_POST['_sort'];
			$criteria->order="$order $sort";
		}else{
			if(method_exists($this, '_order')){
				$this->_order($this->order);
				$criteria->order = $this->order;
			}else
			    $criteria->order="id desc";
		}
		$info=$model->findAll($criteria);
		
		//print_r(CJSON::encode($info));
		if(!empty($param))
			return array_merge(array('info'=>$info,'pages'=>$pages,'count'=>$count,'pagination'=>$pagination), $param);
		return array('info'=>$info,'pages'=>$pages,'count'=>$count,'pagination'=>$pagination);
	}
	
	//add
	function actionAdd(){
		//get Controller
		$_model=ucfirst($this->getId());
		$model =new $_model();
		$table=$model->tableSchema->name;
		if($this->HumpTable($table))
			$_model=$this->HumpTable($table);
		if(isset($_POST["$_model"])){
			$model->attributes=$_POST["$_model"];
			if($model->save()) 
				$this->success('添加信息成功');
			else
				$this->error('添加信息失败');
		}
		$array = array('model'=>$model);
		if(method_exists($this, '_params')){
			$this->_params($this->params);
			$array = array_merge($array, $this->params);
		}
		
		$this->render('add', $array);
	}
	//update
	function actionUpdate($id){
		$_model=ucfirst($this->getId());
		$info =new $_model();
		$model=$info->model();
		$table=$model->tableSchema->name;
		if($this->HumpTable($table))
			$_model=$this->HumpTable($table);
		$result=$model->findByPk($id);
		if(isset($_POST["$_model"])){
			$result->attributes=$_POST["$_model"];
			if($result->save()) 
				$this->success('更新信息成功');
			else
				$this->error(CHtml::errorSummary($model));
		}
		$array = array('model'=>$result);
		if(method_exists($this, '_params')){
			$this->_params($this->params);
			$array = array_merge($array, $this->params);
		}
	
		$this->render('update', $array);
	}
	//delete
	function actionDel($id){
		$_model=ucfirst($this->getId());
		$info =new $_model();
		$model=$info->model();
		if($model->deleteByPk($id)) 		
			$this->display('删除信息成功', 1);
		else
			$this->display('删除信息失败', 0);
	
	}
	//forbid
	function actionForbid($id){
		$_model=ucfirst($this->getId());
		$info =new $_model();
		$model=$info->model();
		$count=$model->updateByPk($id,array('status'=>1));
		if($count>0)
			$this->display('禁用成功', 1);
		else
			$this->display('禁用失败'.CHtml::errorSummary($model), 0);
	}
	//resume
	function actionResume($id){
		$_model=ucfirst($this->getId());
		$info =new $_model();
		$model=$info->model();

		$count=$model->updateByPk($id,array('status'=>0));
		if($count>0)
			$this->display('恢复成功', 1);
		else
			$this->display('恢复失败'.CHtml::errorSummary($model), 0);
	}
	
	
	private  function gets(){
		$criteria = new CDbCriteria;
		$criteria->order="sort asc";
		$criteria->condition="status=1";
		$info=Menu::model()->findAll($criteria);

		$products = json_decode(CJSON::encode($info),TRUE);

        return $products;
	}
	
	private function findMenuTree(){
		//print_r(CJSON::encode($this->gen_tree($this->gets(), 'id', 'parentid')));
		return $this->gen_tree($this->gets(), 'id', 'parentid');
	}
	
	function gen_tree($items, $id = 'id', $pid = 'parentid', $son = 's') {
		$tree = array();
		$tmpMap = array();
		foreach ($items as $item) {
            $tmpMap[$item[$id]] = $item;
		}
		//echo '<pre>';
		//print_r($tmpMap);

		//$tmpMap[$item[$pid]][$son]=array();
		foreach ($items as $key => $item) {
			
			if (isset($tmpMap[$item[$pid]])) {

				$tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
				
			} else {
				$tree[] = &$tmpMap[$item[$id]];
			}
		}
	//	print_r(CJSON::decode(CJSON::encode($tmpMap)));
		unset($tmpMap);
        //echo '<pre>';
        //print_r($tree);
		return $tree;

	}
	
	
	/**
	 * 返回菜单 json
	 */
	public function get_bui() {
		foreach ($this->findMenuTree() as $key => $vars) {
			$m=array();
			$menu[$key]['id'] = $vars['controller'];
			$menu[$key]['homePage'] = $vars['controller'].$vars['action'];
			if (isset($vars['s']) && is_array($vars['s'])) {
				foreach ($vars['s'] as $key2 => $value) {
					$menu[$key]['menu'][$key2]['text'] = $value['name'];
					if (isset($value['s']) && is_array($value['s'])) {
						foreach ($value['s'] as $key3 => $value3) {
									$menu[$key]['menu'][$key2]['items'][$key3] = array(
											'id' => $value3['controller'] . $value3['action'],
											'text' => $value3['name'],
											'href' => $this->createUrl($value3['controller'].'/'.$value3['action']),
											'closeable' => $value3['closeable'] ? true : false,
								);
						}
						if (empty($menu[$key]['menu'][$key2]['items']))
							unset($menu[$key]['menu'][$key2]);
					}
				}
				if (empty($menu[$key]['menu'][$key2]))
					unset($menu[$key]['menu'][$key2]);
	
				if (empty($menu[$key]['menu']))
					unset($menu[$key]);
                else
					$m[]=$vars['id'];
			}
		}
		foreach ($menu as $value) {
			$e[]=$value;
		}
		return array($m,$e);
	}
	
	public function init(){
		$this->uploadPath = Yii::app()->params['uploadPath'];
		$menu = $this->get_bui();
		$access  = Yii::app()->session['_ACCESS_LIST'];
		if(empty(Yii::app()->session['administrator'])){
			foreach ($menu[1] as $k => $v){
				foreach ($v['menu'] as $kk => $vv){
					foreach ($vv['items'] as $kkk => $vvv){
						$href = explode('/', $vvv['href']);	
						$controller = strtoupper($href[count($href) -2]);
						
						if($controller == 'admin.php'){
							$controller = $href[count($href) -1];
							$action = 'INDEX';
						} else {
							$action = strtoupper(str_replace('.html', '', $href[count($href) -1]));
						}
						if(!isset($access['ADMIN'][$controller][$action]) && $controller != 'PUBLIC'){
							unset($menu[1][$k]['menu'][$kk]['items'][$kkk]);
						}
					}
				}	
			}
		}
		//删除空的栏目
        foreach ($menu[1] as $k => $v) {
            foreach ($v['menu'] as $kk => $vv){
                if(empty($vv['items'])){
                    unset($menu[1][$k]['menu'][$kk]);
                    continue;
                }
            }
		}
        //echo '<pre>';
		//print_r($menu[1]);
		$this->bui=CJSON::encode($menu[1]);
		RBAC::$model=$this->id;
		RBAC::$action=operateName($this->id);
		if($_SERVER['REQUEST_URI'] == '/admin.php')
			header("location:../admin.php?r=index/index");
		

		if(!RBAC::AccessDecision()){
			if(!isset(Yii::app()->session[RBAC::$USER_AUTH_KEY]) || empty($access))
				$this->redirect($this->createUrl('public/login'));
		
			$this->display('没有权限', 0, $this->createUrl('public/about'));
		}
		
		$managerId = $_SESSION['authId'];
		$this->mangerInfo = Manager::model()->findByPk($managerId);
	}
	
	function HumpTable($table){
		$str=substr($table, strpos($table, "_")+1);
		if(strpos($str, "_")){
			$str=ucwords(str_replace("_", " ", $str));
			$_model=str_replace(" ", "", $str);
			return $_model;
		}else
			return false;
	}
	//获取名字
    public function getUsername($uid){
    	if(empty($uid))
    		return '';
    	$model=Manager::model()->find(array('select'=>'username,nickname','condition'=>'id=:id','params'=>array(':id'=>''.$uid.'')));
    	if(isset($model))
    		return $model->nickname;
    	else
    		return "异常错误";
    }
   
	//获取数据最后插入id
	function getSqlInsertId(){
		return Yii::app()->db->getLastInsertID();
	}
	//获取用户姓名
	function getRealName(){
		return $this->mangerInfo['real_name'];
	}
	//获取部门ID
	public function getDepId(){
		return $this->mangerInfo['depId'];
	}
	//获取会员ID
	public function getUserid(){
		return $this->getSession(RBAC::$USER_AUTH_KEY);
	}
	public function getListUserId($name){
		$model=Manager::model()->find(array('select'=>'id','condition'=>'nickname=:name','params'=>array(':name'=>''.$name.'')));
		if(!empty($model))
			return $model->id;
		return false;
	}
	public function getGroupUsername($str){
		$arr=explode(',', $str);
		$value="";
		$model=Manager::model()->findAllByPk($arr);
		if(isset($model)){
			foreach ($model as $v){
				$value .=$v->nickname.',';
			}
			return $value;
		}
		else
			return "异常错误";
	}
	//合同搜索员工
	public function getNickname($name){
		$arr=array();
		$result=Manager::model()->findAll(array(
				'select'=>'id',
				'condition'=>'nickname LIKE :name',
				'params'=>array(':name'=>$name)
				));
		foreach ($result as $v){
			$arr[]=$v->id;
		}
		$str=implode(',', $arr);
		return $str;
	}
	
	public function error($msg, $url=''){
		$data['message']=$msg;
		$data['status'] =0;
		$data['url'] =$url;
		exit(CJSON::encode($data));
		
	}
	
	public function success($msg, $url=''){
		$data['message']=$msg;
		$data['status'] =1;
		$data['url'] =$url;
		$data['info'] = array('aaa');
		exit(CJSON::encode($data));
		
	}
	
	public function display($message, $status, $url=NULL, $time = 3) {
		$var['message'] = $message;
		$var['status'] = $status;
		$var['url'] = $url ? $url : Yii::app()->request->urlReferrer;
		$var['time'] = $time;
		$this->render('/public/message', array('var'=>$var));
		exit();
	}

    protected function getChannel(){
        $rs = Channel::model()->getInfo($this->mangerInfo['channel_id']);
        return $rs;
    }
    protected function getGame(){
        $game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
        return $game;
    }
    protected function getServer(){
        $gameServer = GameServer::model()->getInfo($this->mangerInfo['server_id'], $this->mangerInfo['game_id']);
        return $gameServer;
    }
    protected function getServerPre(){
        $gameServer = GameServer::model()->getServer($this->mangerInfo['game_id']);
        return $gameServer;
    }
    protected function getFenbao(){
        $fenbao = Dwfenbao::model()->getInfo($this->mangerInfo['dwFenbao']);
        return $fenbao;
    }
}