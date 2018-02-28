<?php
/**
 * Created by PhpStorm.
 * User: fusha
 * Date: 16-2-29
 * Time: 下午10:07
 *
 * 每日凌晨自动统计程序
 */
set_time_limit(3000);
ini_set('memory_limit', '1024M');
ini_set('display_errors', 'On');
class TsRunHour extends CI_Controller{

	private $contents;
	private $contype;
    public function __construct()
    {
        parent::__construct();
		$this->contents = array(
				1=>array(
						'nm'=>'Players can claim for free a big gift pack if loging in the game today!',
						'zg'=>'今天登录可以免费领取大礼包一份！',
						'yn'=>'Đăng nhập hôm nay nhận ngay 1 phần quà lớn!',
						'els'=>'Cегодня войти в игру и получить большой подарок!',
				),
				2=>array(
						'nm'=>'You have won a chance for free Gashappon! Go and check it!',
						'zg'=>'获得一次免费扭蛋机会，快去看看！',
						'yn'=>'Nhận một lần quay trứng miễn phí, mau đi xem!',
						'els'=>'Вы получили 1 раз шанс сделать Гасяпон, посмотри!',
				),
				3=>array(
						'nm'=>'New arrivals has come in the shopping mall! Go and open it!',
						'zg'=>'商店又新进了一批新的货物，打开看看！',
						'yn'=>'Shop vừa nhập lô hàng mới, mở xem!',
						'els'=>'В магазине есть новые предметы, вперед!',
				),
				
				4=>array(
						'nm'=>'A new wave of bonus come! All are of great value!',
						'zg'=>'又上架了一波福利，都是干货！',
						'yn'=>'Lại mới lên một đợt phúc lợi mới, toàn hàng hiếm!',
						'els'=>'Новый бонус ждет вас, не пропустите!',
				),
				5=>array(
						'nm'=>'VIT has fully recovered! Go open it!',
						'zg'=>'体力恢复满了，快打开看看！',
						'yn'=>'Thể lực hồi phục đầy, mau đi xem!',
						'els'=>'Сила была восстановлена, посмотри!',
				),
		);
		//$this->contype = 'zg';
    }
   
    function getmessage($gameid){
    	switch ($gameid){
    		case '100':
    			return 'nm';
    		default:
    			return 'nm';	
    	}
    }
    /**
     * cli模式运行
     *
     * 新角色玩家隔天推送
     */
    public function send2login()
    {
    	$dbsdk = $this->load->database('sdk', true);
    	$date = date('Ymd');
    	//$message = $this->contents[1][$this->contype];
    	$sql = "SELECT regid,devicetype as type,game_id from send2_login a inner join push_regid b on a.mac=b.last_login_mac where a.logdate=$date and b.logdate<$date";
    	$query = $dbsdk->query($sql);
    	if($query){
    		$data = $query->result_array();
    		if($data){
    			foreach ($data as $k=>$v){
    				$v['message'] = $this->contents[1][$this->getmessage($v['game_id'])];
    				$result = $this->https_post($v);
    				usleep(1000);
    			}
    		}
    	}
    	unset($dbsdk);
    }
    /**
     * cli模式运行
     *
     * 最近三天都有登录的玩家
     */
    public function send3login()
    {
        $dbsdk = $this->load->database('sdk', true);
        $date = date('Ymd');
        $h = date('H');
        if($h>=20){
        	$message = '疯狂的超梦出现了，大木博士正在集结勇士击败它';
        }elseif($h>=18){
        	$message = '豪华晚餐时间到了，快登录游戏领取体力吧';
        }else{
        	$message = '豪华午餐时间到了，快登录游戏领取体力吧';
        }
        $sql = "SELECT regid,devicetype as type,game_id from send3_login a inner join push_regid b on a.mac=b.last_login_mac where a.logdate=$date and b.logdate<$date";
        $query = $dbsdk->query($sql);
        if($query){
        	$data = $query->result_array();
        	if($data){
        		foreach ($data as $k=>$v){
        			$v['message'] = $message;
        			$result = $this->https_post($v);
        			usleep(1000);
        		}
        	}
        }
        unset($dbsdk);
    }
    
    /**
     * cli模式运行
     *
     * 超过三天没有登录的玩家
     */
    public function send3nologin()
    {
    	$dbsdk = $this->load->database('sdk', true);
        $date = date('Ymd');
        $marr = [2,3,4,5];
        //$message = $this->contents[$marr[rand(0, 3)]][$this->contype];
        $sql = "SELECT regid,devicetype as type,game_id from send3_nologin a inner join push_regid b on a.mac=b.last_login_mac where a.logdate=$date and b.logdate<$date";
        $query = $dbsdk->query($sql);
        if($query){
        	$data = $query->result_array();
        	if($data){
        		foreach ($data as $k=>$v){
        			$v['message'] = $this->contents[$marr[rand(0, 3)]][$this->getmessage($v['game_id'])];
        			$result = $this->https_post($v);
        			usleep(1000);
        		}
        	}
        }
        unset($dbsdk);
    }
    
    
function https_post($data) {
	$url = include APPPATH .'/config/ts.php'; 
	$url .= 'tuisong/sent.php';
	$str = '';
	if ($data) {
		foreach ( $data as $key => $value ) {
			$str .= $key . "=" . $value . "&";
		}
	}
	$curl = curl_init ( $url ); // 启动一个CURL会话
	curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
	curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 2 ); // 从证书中检查SSL加密算法是否存在
	// curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
	curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
	if ($str) {
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $str ); // Post提交的数据包
	}
	curl_setopt ( $curl, CURLOPT_TIMEOUT, 5 ); // 设置超时限制防止死循环
	// curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
	// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$tmpInfo = curl_exec ($curl);
	curl_close ($curl);
	return $tmpInfo;
}

}
