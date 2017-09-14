<?php
/**
 * 大小周及假日表
 */
namespace Web\Controller;
use Think\Controller;
class TimeController extends Controller {
 
    public function index(){
    	setTemp('zrfilm');
    	/****假日时间****/
    	$array[1]=array('1','2','3');
    	$array[2]=array('7','8','9','10','11','12','13');
    	$array[4]=array('2','3','4','30');
    	$array[5]=array('1','2');
    	$array[6]=array('9','10','11');
    	$array[9]=array('15','16','17');
    	$array[10]=array('1','2','3','4','5','6','7');
    	/****加班时间****/
    	$_array[2]=array('6','14');
    	$_array[6]=array('12');
    	$_array[9]=array('18');
    	$_array[10]=array('8','9');
    	$y='2016';
    	$this->assign('y',$y);
    	//$weekss=session('year'.$y);
    	if(empty($weekss)){
    		for($t=1;$t<=12;$t++){
    			$n=$t;
    			$tday=date('t',strtotime($y.'-'.$n));
    			$tmonth=$y.'-'.$n;
    			$j=0;
    			for ($i=1;$i<=$tday;$i++){
    				$week=date('w',strtotime($tmonth.'-'.$i));
    				$weeks[$j][$week]['d']=date('j',strtotime($tmonth.'-'.$i));
    				$weeks[$j][$week]['day']=strtotime($tmonth.'-'.$i);
    				if($week==6){
    					$j+=1;
    				}
    			}
    			$minw=7-count($weeks[0]);
    			$maxw=count($weeks[count($weeks)-1]);
    			if($minw>0){
    				if($t>1){
    					$n=$t-1;
    					$tday=date('t',strtotime($y.'-'.$n));
    					for ($i=1;$i<=$minw;$i++){
    						$weeks[0][$i-1]['d']=$tday-$minw+$i;
    						$weeks[0][$i-1]['day']=strtotime($y.'-'.$n.'-'.($tday-$minw+$i));
    					}
    				}else{
    					$n=12;
    					$tday=date('t',strtotime(($y-1).'-'.$n));
    					for ($i=1;$i<=$minw;$i++){
    						$weeks[0][$i-1]['d']=$tday-$minw+$i;
    						$weeks[0][$i-1]['day']=strtotime(($y-1).'-'.$n.'-'.($tday-$minw+$i));
    					}
    				}
    			}
    			$cw=count($weeks);
    			if($t<12){
    				$n=$t+1;
    				if($maxw<7){
    					for ($i=1;$i<=7-$maxw;$i++){
    						$weeks[$j][$maxw+$i-1]['d']=$i;
    						$weeks[$j][$maxw+$i-1]['day']=strtotime($y.'-'.$n.'-'.$i);
    					}
    				}
    				if($cw<6){
    					for ($i=0;$i<7;$i++){
    						$weeks[5][$i]['d']=8-$maxw+$i;
    						$weeks[5][$i]['day']=strtotime($y.'-'.$n.'-'.(8-$maxw+$i));
    					}
    				}
    			}
    			foreach ($weeks as $key=>$week){
    				ksort($weeks[$key]);
    				foreach ($week as $k=>$v){
    					$ds=(date('z',$v['day'])-2)%14;
    					if(in_array($ds, array(0,6,7,-8,-7))){
    						if(!in_array($v['d'], $_array[date('n',$v['day'])])){
    							$weeks[$key][$k]['isfun']=1;
    						}
    					}else{
    						if(in_array($v['d'], $array[date('n',$v['day'])])){
    							$weeks[$key][$k]['isfun']=1;
    						}
    					}
    				}
    			}
    			$weekss[$t]=$weeks;
    			unset($weeks);
    		}
    		session('year'.$y,$weekss);
    	}
    	$this->assign('weekss',$weekss);
    	$this->display();
    }

    function getday(){
    	$y='2016';
    	$start=I('start');
    	$startr=strtotime($y.'-'.$start);
    	$day=I('day');
    	$flag=session('c'.$start.$day);
    	if(empty($flag)){
    		$c=0;
    		$weekss=session('year'.$y);
    		foreach ($weekss as $k=>$weeks){
    			foreach ($weeks as $week){
    				foreach ($week as $v){
    					if(date('m',$v['day'])==$k&&$v['day']>=$startr&&$v['isfun']!='1'){
    						$c++;
    						if($c==$day){
    							$flag=date('n-j',$v['day']);
								break;
    						}
    					}
    				}
    				if(!empty($flag)){
    					break;
    				}
    			}
    			if(!empty($flag)){
    				break;
    			}
    		}
    		if(empty($flag)){
    			$flag='已超出2016年';
    		}
    	}
    	echo  json_encode($flag);
    }
	
}