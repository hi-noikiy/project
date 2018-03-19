<?php


include_once 'MY_Controller.php';
class PlayerAnalysisNew extends MY_Controller {
  
    public $player_analysis_new_model;
    public function __construct()
    {
        parent::__construct();
    }
    
    /*
     * 	多人对战-精灵使用率统计
     */
    public function pvpCombat(){
        

        if (parent::isAjax()) {
             $date = $this->input->get('date1') ;
             $date2 = $this->input->get('date2');
             
             $viplev_min= $this->input->get('viplev_min');
             $viplev_max= $this->input->get('viplev_max');
            
            $where['date']=date('Ymd',strtotime ( $date ));		
            $where['date2']=date('Ymd',strtotime ( $date2 ));
   
         
            $group="eud_id";
            $field="eud_id,count(*) as appear_total,count(if(win_flag=1,true,null)) as win_total, count(if(win_flag=0,true,null)) as defeated";
            $where['serverids'] = $this->input->get('server_id');
            $this->load->model ( 'Player_analysis_new_model' );
            $data = $this->Player_analysis_new_model->pvpCombat($table , $where, $field, $group, $order, $limit);
            
   
            foreach ($data['bout'] as $v){
                
                $bout_total+=$v['bout_total'];  //所有总的场次
            }
            
           // var_dump($data);

            $items = include APPPATH .'/config/item_types.php'; //道具字典
            foreach ( $data as &$v ) {
                $v['elf'] = $v['eud_id'].$items[$v['eud_id']];
                
                $v['appear_rate'] =100*(round($v['appear_total']/$bout_total,4)).'%';
                
                $v['win_total'] =$v['win_total'];
                $v['defeated'] =$v['defeated'];                
                
                $win_and_defeated=$v['defeated']+$v['win_total'];
                
                if($win_and_defeated==0){
                    $v['win_rate']=0;
                } else {           
                    $v['win_rate'] =100*(round($v['win_total']/$win_and_defeated*1,4)).'%';
                }
           //   $v['text']="<a href='javascript:detail($v[eud_id], 1)'>技能配置</a>";
            
            }
            

            if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data]);
            else echo json_encode(['status'=>'fail','info'=>'未查到数据']);
        }else{
    
            $this->data['page_title']="多人对战-精灵使用率统计 ";
            $this->data ['viplev_filter'] = true;
            $this->data ['hide_channel_list'] = true;
            $this->body = 'PlayerAnalysis/pvpCombat';
            $this->layout();
        }
        
    }

  
    /*
     * 	V12玩家的信息统计 zzl   20171123
     */
    public function userVip(){
    
    
        if (parent::isAjax()) {
            $date = $this->input->get('date1') ;
            $date2 = $this->input->get('date2');
             
            $viplev_min= $this->input->get('viplev_min');
            $viplev_max= $this->input->get('viplev_max');
    
            $where['date']=date('Ymd',strtotime ( $date ));
            $where['date2']=date('Ymd',strtotime ( $date2 ));
            $act_types = include APPPATH .'/config/activity_list.php'; //运营类型字典
             
             
            $group="";
            $field="count(DISTINCT accountid) p1,sum(vip_exp) p2,avg(user_level) p30,avg(prestige_avg) p31,avg(handbook_avg) p32,avg(intimacy_avg) p33,sum(effort_avg) p34";
            
        
            
            
            $where['serverids'] = $this->input->get('server_id');
            $this->load->model ( 'Player_analysis_new_model' );
            $data_result = $this->Player_analysis_new_model->userVip($table , $where, $field, $group, $order, $limit);
            
          //  var_dump($data_result);
            
         //   var_dump($data);
    
            $data=$data_result[0];
            $data['p3']=$data_result['type0']['0']['p3']?$data_result['type0']['0']['p3']:0;
            $data['p4']=$data_result['type0']['0']['p4']?$data_result['type0']['0']['p4']:0;
            $data['p5']=$data_result['type0']['0']['p5']?$data_result['type0']['0']['p5']:0;
            $data['p39']=$data_result['type0']['0']['p39']?$data_result['type0']['0']['p39']:0;
            $data['p40']=$data_result['type0']['0']['p40'];
            $data['p41']=$data_result['type0']['0']['p41'];
            
            
            
            $data['p7']=$data_result['type1']['0']['p7'];
            $data['p7_2']=$data_result['type1']['0']['p7_2'];
            $data['p43']=$data_result['type1']['0']['p43'];
            $data['p43_2']=$data_result['type1']['0']['p43_2'];
            $data['p8']=$data_result['type1']['0']['p8']?$data_result['type1']['0']['p8']:0;
            $data['p44']=$data_result['type1']['0']['p44']?$data_result['type1']['0']['p44']:0;
            
            $data['p9']=$data_result['type2']['0']['p9'];
            $data['p10']=$data_result['type2']['0']['p10'];
            $data['p10_2']=$data_result['type2']['0']['p10_2'];
            $data['p45']=$data_result['type2']['0']['p45'];
            $data['p46']=$data_result['type2']['0']['p46'];
            $data['p46_2']=$data_result['type2']['0']['p46_2']?$data_result['type2']['0']['p46_2']:0;
            $data['p11']=$data_result['type3']['0']['p11']?$data_result['type3']['0']['p11']:0;
            $data['p12']=$data_result['type3']['0']['p12']?$data_result['type3']['0']['p12']:0;
            $data['p13']=$data_result['type4']['0']['p13']?$data_result['type4']['0']['p13']:0;
            $data['p15']=$data_result['type5']['0']['p15']?$data_result['type5']['0']['p15']:0;
            $data['p16']=$data_result['type5']['0']['p16']?$data_result['type5']['0']['p16']:0;
      
            $data['p17']=1;
            $data['p18']=0;
            $data['p19']=0;
            $data['p20']=0;
            $data['p21']=$data_result['type8']['0']['p21']?round($data_result['type8']['0']['p21'],2):0;
            $data['p22']=$data_result['type8']['0']['p22']?round($data_result['type8']['0']['p22'],2):0;
            $data['p23']=$data_result['type9']['0']['p23']?round($data_result['type9']['0']['p23'],2):0;
            $data['p24']=$data_result['type9']['0']['p24']?round($data_result['type9']['0']['p24'],2):0;
            $data['p25']=$data_result['type10']['0']['p25']?round($data_result['type10']['0']['p25'],2):0;
            $data['p26']=$data_result['type10']['0']['p26']?round($data_result['type10']['0']['p26'],2):0;
            

             
             foreach ($data_result['type15'] as $k=>$v){
                 
                foreach ($act_types as $k2=>$v2){
                    
                  if($v['param']==$k2){
                      $v['name']=$v2;
                  }
                }
                
                $data2[$k]=$v;
                   
             }
             unset($data2[0]);
        //   var_dump($data2);
             
             
              
            if (!empty($data)) echo json_encode(['status'=>'ok', 'data'=>$data,'data2'=>$data2]);
            else echo json_encode(['status'=>'fail','info'=>'未查到数据']);
        }else{
    
            $this->data['page_title']="玩家分析>V12玩家的信息统计 ";
            
            $this->data ['hide_end_time'] = true;
        //    $this->data ['viplev_filter'] = true;
            $this->data ['hide_channel_list'] = true;
            $this->body = 'PlayerAnalysis/userVip';
            $this->layout();
        }
    
    }

        /*
     * adventure 20171207 zzl
     */
    public function adventure()
    {
        if (parent::isAjax()) {
            
            $date = $this->input->get('date1');
            $date2 = $this->input->get('date2');
            
            $viplev_min = $this->input->get('viplev_min');
            $viplev_max = $this->input->get('viplev_max');
            
            $where['date'] = date('Ymd', strtotime($date));
            $where['date2'] = date('Ymd', strtotime($date2));
            $act_types = include APPPATH . '/config/activity_list.php'; // 运营类型字典
            
            $group = "vip_level";
            $field = "vip_level,accountid,count(DISTINCT accountid) cnt,adventure_lev,adventure_num";
            $order = "vip_level desc";
            
            $where['serverids'] = $this->input->get('server_id');
            $this->load->model('Player_analysis_new_model');
            $data_result = $this->Player_analysis_new_model->adventure($table, $where, $field, $group, $order, $limit);
            
            foreach ($data_result as $k => $v) {
                
                foreach ($data_result['more'] as $v2) {
                    if ($v['vip_level'] == $v2['vip_level']) {
                        
                        if ($v2['adventure_lev'] == 1) {
                            $v['v1'] += $v2['cnt'];
                            $v['v1ex'] += $v2['adventure_num'];
                        }
                        if ($v2['adventure_lev'] == 2) {
                            $v['v2'] += $v2['cnt'];
                            $v['v2ex'] += $v2['adventure_num'];
                        }
                        if ($v2['adventure_lev'] == 3) {
                            $v['v3'] += $v2['cnt'];
                            $v['v3ex'] += $v2['adventure_num'];
                        }
                        if ($v2['adventure_lev'] == 4) {
                            $v['v4'] += $v2['cnt'];
                            $v['v4ex'] += $v2['adventure_num'];
                        }
                    }
                }
                $data_new[$k] = $v;
            }
            
            foreach ($data_new as &$v) {
                
                $v['v1'] = $v['v1'] ? $v['v1'] : 0;
                $v['v2'] = $v['v2'] ? $v['v2'] : 0;
                $v['v3'] = $v['v3'] ? $v['v3'] : 0;
                $v['v4'] = $v['v4'] ? $v['v4'] : 0;
                $v['v1e'] = $v['v1ex'] ? round(($v['v1ex'] / $v['v1']), 4) : 0;
                $v['v2e'] = $v['v2ex'] ? round(($v['v2ex'] / $v['v2']), 4) : 0;
                $v['v3e'] = $v['v3ex'] ? round(($v['v3ex'] / $v['v3']), 4) : 0;
                $v['v4e'] = $v['v4ex'] ? round(($v['v4ex'] / $v['v4']), 4) : 0;
            }
            
            unset($data_new['more']);
            $data = $data_new;
            
            if (! empty($data))
                echo json_encode([
                    'status' => 'ok',
                    'data' => $data
                ]);
            else
                echo json_encode([
                    'status' => 'fail',
                    'info' => '未查到数据'
                ]);
        } else {
            
            $this->data['page_title'] = "玩家分析-冒险称号统计 ";
            
            $this->body = 'PlayerAnalysis/adventure';
            $this->layout();
        }
    }
    
    
    /*
     * 	每日冒险结果统计 zzl    
     */
    public function everyAdventure()
    {
        if (parent::isAjax()) {
    
           $date = $this->input->get('date1');
            $date2 = $this->input->get('date2');
    
            $viplev_min = $this->input->get('viplev_min');
            $viplev_max = $this->input->get('viplev_max');
            
        
            $server_start = $this->input->get('server_start')? $this->input->get('server_start'):'';
            $server_end= $this->input->get('server_end')?$this->input->get('server_end'):'';        
     
            $where['server_start']=$server_start?date ( 'Ymd', strtotime ( $server_start ) ):'';      
            $where['server_end']=$server_end?date ( 'Ymd', strtotime ( $server_end ) ):'';
    
            $where['date'] = date('Ymd', strtotime($date));
            $where['date2'] = date('Ymd', strtotime($date2));
            $act_types = include APPPATH . '/config/activity_list.php'; // 运营类型字典
    
            $group = "vip_level";
            $field = "vip_level,accountid,count(DISTINCT accountid) cnt,count(*) total,count(if(param=3,true,null)) p3,count(if(param=2,true,null)) p2,count(if(param=1,true,null)) p1, count(if(param=4,true,null)) p4";
            $order = "";
    
            $where['serverids'] = $this->input->get('server_id');
            $this->load->model('Player_analysis_new_model');
            $data_result = $this->Player_analysis_new_model->everyAdventure($table, $where, $field, $group, $order, $limit);  
            
            $data_result_Behavior = $this->Player_analysis_new_model->everyAdventureBehavior($table, $where, $field, $group, $order, $limit);
            
            
            
            
            
           $field = "vip_level,accountid,count(DISTINCT accountid) cnt,count(*) total,sum(item_num) money";
           $data_result_more = $this->Player_analysis_new_model->adventureAward($table, $where, $field, $group, $order, $limit);
           
           
          /*vipDistribution
           * 
           */
       
           $this->load->model('SystemFunction_model');           
           $data_result_vip = $this->SystemFunction_model->vipDistribution($where, $field, $group);
           
       //  var_dump($data_result_vip['day0']);
           
           foreach ($data_result as &$v){
               
               
               foreach ($data_result_more as $v2){
                    
                    if($v['vip_level']==$v2['vip_level']){
                        
                        $v['participation']=$v2['cnt'];
                        $v['money']=$v2['money'];
                    }
               }
               
               
               foreach ($data_result_vip['day0'] as $v3){
               
               
                   if($v['vip_level']==$v3['viplev']){
                       $v['active']=$v3['accountid_total'];
                       
                       $v['rate']=round(($v['participation']/$v3['accountid_total'])*100,4);
                   
                   }
               
               
               }
               
               foreach ($data_result_Behavior as $v4){
                       if($v['vip_level']==$v4['vip_level']){
                       	
                       $v['p1']=$v4['p1'];
                       $v['p2']=$v4['p2'];
                       $v['p3']=$v4['p3'];
                       $v['p4']=$v4['p4'];
                       $v['cnt']=$v4['cnt'];
                       $v['total']=$v4['total'];
                      
                   
                   }
               }
        
               
               
               
           }
   
        //   var_dump($data_result);
           
   
            if (! empty($data_result))
                echo json_encode([
                    'status' => 'ok',
                    'data' => $data_result
                ]);
                else
                    echo json_encode([
                        'status' => 'fail',
                        'info' => '未查到数据'
                    ]);
        } else {
    
            $this->data['page_title'] = "玩家分析-每日冒险称号统计 ";
            $this->data ['hide_end_time'] = true;
            $this->body = 'PlayerAnalysis/everyAdventure';         
            $this->layout();
        }
    }
    
    

    
}
