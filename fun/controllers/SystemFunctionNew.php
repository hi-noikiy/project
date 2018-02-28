<?php
ini_set ( 'display_errors', 'On' );

include 'MY_Controller.php';
ini_set ( 'memory_limit', '1024M' );
class SystemFunctionNew extends MY_Controller {
	/**
	 *
	 * @var $SystemFunction_model SystemFunction_Model
	 */
	public $SystemFunctionNew_model;
	public function __construct() {
		parent::__construct ();
		 $this->load->model('player_analysis_new_model');
		$this->load->model ( 'SystemFunctionNew_model' );
		$this->SystemFunctionNew_model->setAppid ( $this->appid );
	}
	/**
	 *    	多人对战-匹配时间查询 
	 */
	public function multiplayerMatchTime() {
        

	    if (parent::isAjax ()) {
	        $date = $this->input->get ( 'date1' );
	        $date2 = $this->input->get ( 'date2' );
	        $where ['begindate'] = date('Ymd',strtotime ( $date ));
	        $where ['enddate'] = date('Ymd',strtotime ( $date2 ));
	        $viplev_min= $this->input->get('viplev_min');
	        $viplev_max= $this->input->get('viplev_max');
	       // $where['gametype'] = $this->input->get ( 'gametype' );//对战类型
	        $where['gametype']=5;
	        $where ['serverids'] = $this->input->get ( 'server_id' ); // 区服
	        $where ['dan'] = $this->input->get ( 'dan' ); //段位
	        $where ['danend'] = $this->input->get ( 'danend' ); //结束段位
	        
	        $where['viplev_min']=$viplev_min;
	        $where['viplev_max']=$viplev_max;
	        $this->load->model ( 'GameServerData' );
			$field = 'matchtime,count(*) c';
			$group  = 'matchtime';
			$data = $this->GameServerData->match ( $where,$field,$group );
	        $this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( [
	            'status' => 'ok',
	            'data' => $data
	        ] ) );
	    } else {
	        $this->data['page_title']="系统功能统计>多人对战-匹配时间查询";
	        $this->body = 'SystemFunction/multiplayerMatchTime';
	 /*        $this->data ['viplev_filter'] = true;
	        $this->data ['hide_channel_list'] = true;
	        $this->body = 'SystemFunction/multiplayerMatchTime'; */
	        $this->layout ();
	    }	    

    }

 
    //系统功能统计-多人对战战斗回合
    public function multiplayerBout(){



        if (parent::isAjax()) {
            $date = $this->input->get('date1') ;
            $date2 = $this->input->get('date2');
             
            $viplev_min= $this->input->get('viplev_min');
            $viplev_max= $this->input->get('viplev_max');
        
            $where['date']=date('Ymd',strtotime ( $date ));
            $where['date2']=date('Ymd',strtotime ( $date2 ));
            $where['viplev_min']=$viplev_min;
            $where['viplev_max']=$viplev_max;
             
             
            $group="turn_num";
            $order="turn_num";
            $field="turn_num";
            $where['serverids'] = $this->input->get('server_id');
            $this->load->model ( 'SystemFunctionNew_model' );
            $data = $this->SystemFunctionNew_model->multiplayerBout($table , $where, $field, $group, $order, $limit);
            
            
            
            foreach ($data as $k=>&$v){            
                foreach ($data['more'] as $k2=>$v2){
                if($v['turn_num']==$v2['turn_num']){
                    $v['total']++;
                }            
                
                }                
            }
        
        
        
        
            if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
            else echo json_encode(['status'=>'fail','info'=>'未查到数据']);
        }else{
        
            $this->data['page_title']="系统功能统计>多人对战战斗回合";
         //   $this->data ['hide_server_list'] = true;
           //   $this->data ['viplev_filter'] = true;
            $this->data ['hide_channel_list'] = true;
            $this->body = 'SystemFunction/multiplayerBout';
            $this->layout();
        }
        
        
        
        
    }
    
    // 	多人对战-技能使用次数统计
    public function multiplayerSkill(){

          
            $skill_type=array(1=>"物理",2=>"特殊",3=>"变化");
            $target_type=array(1=>"敌方单体",2=>"自身",3=>"敌方场地",4=>"我方场地",5=>"天气",6=>"我方单体",7=>"敌方全体",8=>"我方全体",9=>"全体",10=>"全场地");
        
        
        if (parent::isAjax()) {
            $date = $this->input->get('date1') ;
            $date2 = $this->input->get('date2');
             
            $viplev_min= $this->input->get('viplev_min');
            $viplev_max= $this->input->get('viplev_max');
            
            $where['viplev_min']=$viplev_min;
            $where['viplev_max']=$viplev_max;
        
            $where['date']=date('Ymd',strtotime ( $date ));
            $where['date2']=date('Ymd',strtotime ( $date2 ));
             
             
            $group="u.magic_id";
            $order="u.magic_id";
            $field="u.magic_id,count(*) as cnt,s.name,s.system,s.target";
            $where['serverids'] = $this->input->get('server_id');
            $this->load->model ( 'SystemFunctionNew_model' );
            $data = $this->SystemFunctionNew_model->multiplayerSkill($table , $where, $field, $group, $order, $limit);
            
            foreach ($data as &$v){
                $v['system']=$skill_type[$v[system]];
                $v['target']=$target_type[$v[target]];
                
            }
        
        
        
        
            if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
            else echo json_encode(['status'=>'fail','info'=>'未查到数据']);
        }else{
        
            $this->data['page_title']="多人对战>多人对战技能使用率";
            $this->data ['hide_server_list'] = true;
          //  $this->data ['viplev_filter'] = true;
            $this->data ['hide_channel_list'] = true;
            $this->body = 'SystemFunction/multiplayerSkill';
            $this->layout();
        }
       
    }
    
    /*
     * 洛托姆强化   zzl 20171202
     */
    public function intensify(){
        if (parent::isAjax()) {
            $date = $this->input->get('date1') ;
            $date2 = $this->input->get('date2');
         
            $where['date']=date('Ymd',strtotime ( $date ));
            $where['date_table']=date('Ym',strtotime ( $date ));
            $where['date2']=date('Ymd',strtotime ( $date2 ));
             
             
            $group="Rotom_class";
            $order="";
            $field="Rotom_class,COUNT(DISTINCT accountid) cnt";
            $where['serverids'] = $this->input->get('server_id');
            $this->load->model ( 'SystemFunctionNew_model' );
            $data_result = $this->SystemFunctionNew_model->intensify($table , $where, $field, $group, $order, $limit);            
 
            
            $field_user="COUNT(DISTINCT accountid) cnt";
            $group_user='';
            $this->load->model ( 'Data_analysis_model' );
            $logininfo = $this->Data_analysis_model->activeVip($table , $where, $field_user, $group_user, $order, $limit);
            
            
        
            foreach ($data_result as $k=>$v){
             $k_new=$k+3;
                foreach ($data_result['more'] as $v2){
                    if($v['Rotom_class']==$v2['Rotom_class']) {
   
                    }                
                }
                if(empty($v['cnt'])){$v['cnt']=0;}
                $v['Rotom_class']=$class=$v['Rotom_class']?$v['Rotom_class']:0;
                $v['name']="开启".$class."阶洛托姆玩家数";
                $v['text1']="<a href='javascript:classDetail({$v[Rotom_class]}, $where[date])'>服务器分布</a>";
                $v['text2']="<a href='javascript:vipDetail({$v[Rotom_class]}, $where[date])'>VIP分布</a>";
             $data_1[$k]=$v;
            }         
     
            $data_1[10]['name']="平均强化等级";
            $data_1[10]['cnt']=$data_result['more2'][0]['avg'];
            $data_1[10]['text1']="<a href='javascript:classDetail(100,{$where['date']})'>服务器分布</a>";
            $data_1[10]['text2']="<a href='javascript:vipDetail(100,{$where['date']})'>VIP分布</a>";          
            
          
            $data_2[11]['name']="活跃玩家数";
            $data_2[11]['cnt']=  $logininfo[0]['cnt'];
            $data_2[11]['text1']="";
            $data_2[11]['text2']="";  
          
            $data=array_merge($data_2,$data_1);
        
            if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
            else echo json_encode(['status'=>'fail','info'=>'未查到数据']);
        }else{
        
            $this->data['page_title']="洛托姆强化";
            $this->data ['hide_server_list'] = true;
            $this->data ['hide_end_time'] = true;            
           
            $this->data ['hide_channel_list'] = true;
            $this->body = 'SystemFunction/intensify';
            $this->layout();
        }
        
         
        
        
        
    }

}
