	<?php
/**
玩家时长 20171010 zzl
*/

defined('BASEPATH') OR exit('No direct script access allowed');
class ApiPlay extends CI_Controller{

	
/*  	public function __construct(){
		
		parent::__construct();
 		if ( $this->input->server('REQUEST_METHOD') != 'POST') {
 			set_status_header(401);
 			echo 'Invalid Request!!!';
 			exit;
 		}
	
		
	
	} */
	
	
	public function playerOnline(){
	    
	    $request['data']=array(
	        'serverid'=>8001,
	        'userid'=>1000198,
	        'online_date'=>20170117,
	    );
	    
	    
	    $post_data=json_encode($request);
	    
	    echo $post_data;
	    
	  //  $post_data=$this->input->post();
	     $post_data= json_decode($post_data, true);
	    $this->data= $post_data['data'];
	    
	echo    $this->data['serverid'];
	echo     $this->data['userid'];
	 echo     $this->data['online_date'];
	    
	   // var_dump( $this->data );
	  
	    
	    
/* 	    $serverid = $this->input->post('serverid');
	    $userid = $this->input->post('userid');
	    $date = $this->input->post('date'); //格式  20171010 */
	    
	  //  $this->load->database('sdk');
	    $this->db = $this->load->database ( 'sdk', TRUE );
	
		if($this->data['serverid'] && $this->data['userid'] &&  $this->data['online_date']){
		 echo   $sql = "SELECT online,userid,serverid,online_date FROM u_dayonline WHERE serverid='{$this->data['serverid']}' and userid='{$this->data['userid']}' and online_date={$this->data['online_date']} ";
		    $query = $this->db->query($sql);
		}
	
		if ( $query ) {
			$result_data= $query->result_array () ;
		} else{
		   $result_data='';
        }
         $result['data']=json_encode($result_data);
         echo "111";
         var_dump($result);
          
          return  $result;
	}
	
	
}