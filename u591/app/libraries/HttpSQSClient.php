<?php

/**
 * Created by PhpStorm.
 * User: guangpeng
 * Date: 9/28-028
 * Time: 11:51
 */
function logmsg($file_name, $log_path, $log_content)
{
    echo $file_name, $log_path,$log_content;
}
class HttpSQSClient
{
    const KEEPALIVE_TIME = 60;
    const HTTPSQS_TIMEOUT = 10;// httpsqs读写超时时间
    public $httpsqs_host;
    public $httpsqs_port;
    public $httpsqs_auth;
    public $httpsqs_charset;
    /* BEGIN: Added by zhongjun 20130515 */
    public $get_result = null;
    /* END: Added by zhongjun 20130515 */

    public function __construct($host='127.0.0.1', $port=1218, $auth='', $charset='utf-8') {
        $this->httpsqs_host = $host;
        $this->httpsqs_port = $port;
        $this->httpsqs_auth = $auth;
        $this->httpsqs_charset = $charset;
        return true;
    }

    public static function log($word){
        $date = date('Ymd');
        if (PHP_SAPI=='cli') {
            $date = "cli_$date";
        }
        $filepath= LOG_PATH . '/sdk';
        $word = date("Y-m-d H:i:s").'#'. $word;
        $fp = fopen($filepath,"a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"\n".$word);
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    public function &http_get($query)
    {
        static $socket = NULL;
        static $last_time = 0;


// reset $get_result
        $this->get_result = null;
        $this->get_result = array();

        if ($last_time == 0)
        {
            $last_time = time();
        }

        $now = time();

        if ($now >= $last_time + self::KEEPALIVE_TIME)
        {
            /* BEGIN: Modified by zhongjun 20130504 */
            /* 只是将$socket重置，应该存在发生资源泄漏的可能 */
            if (isset($socket))
            {
                @fclose($socket);
                $socket = NULL;
            }
            /* BEGIN: Modified by zhongjun 20130504 */
        }

        if (is_null($socket))
        {
            $socket = fsockopen($this->httpsqs_host, $this->httpsqs_port, $errno, $errstr, 1);

            if (!$socket)
            {
                self::log("fsockopen failed in http_get");

                $this->get_result = false;
                return $this->get_result;
            }

            stream_set_timeout($socket, self::HTTPSQS_TIMEOUT);
        }

        // 组装get报文
        $out = "GET ${query} HTTP/1.1\r\n";
        $out .= "Host: {$this->httpsqs_host}\r\n";
        $out .= "Connection: Keep-Alive\r\n";
        $out .= "Keep-Alive:".self::KEEPALIVE_TIME."\r\n";
        $out .= "\r\n";

        // 发送get请求
        $ret = fwrite($socket, $out);
        if ((false === $ret) || ($ret != strlen($out)))
        {
            // 发送get请求出错，或超时
            self::log("write GET request failed in http_get");

            fclose($socket);
            $socket = NULL;

            $this->get_result = false;
            return $this->get_result;
        }

        // 当请求发送成功时，更新$last_time
        $last_time = $now;

        $line = trim(fgets($socket));
        $header .= $line;
        list($proto, $rcode, $result) = explode(" ", $line);
        $len = -1;

        while (($line = trim(fgets($socket))) != "")
        {
            $header .= $line."\n";
            if (strstr($line, "Content-Length:"))
            {
                list($cl, $len) = explode(" ", $line);
            }
            if (strstr($line, "Pos:"))
            {
                list($pos_key, $pos_value) = explode(" ", $line);
            }
            if (strstr($line, "Connection: close"))
            {
                $close = true;
            }
        }

        if ($len < 0)
        {
// 接收应答消息的http头失败
            self::log("read http header failed in http_get");

            fclose($socket);
            $socket = NULL;

            $this->get_result = false;
            return $this->get_result;
        }

        // 接收消息体，最多尝试3次
        $body = fread($socket, $len);

        $fread_times = 0;
        while(strlen($body) < $len)
        {
            if ($fread_times > 1)
            {
                break;
            }

            $body1 = fread($socket, $len - strlen($body));

            $body .= $body1;
            unset($body1);

            $fread_times++;
        }

        if (strlen($body) < $len)
        {
            // fread出错或超时
            self::log("read http body failed in http_get, length:$len, the received body:\n$body");

            fclose($socket);
            $socket = NULL;

            $this->get_result = false;
            return $this->get_result;
        }

        if ($close)
        {
            self::Log("http_get: the CONNECTION option is CLOSE in http header");
            fclose($socket);
            $socket = NULL;
        }

        $this->get_result["pos"] = (int)$pos_value;
        $this->get_result["data"] = $body;

        return $this->get_result;
    }





    public function http_post($query, &$body)
    {
        static $socket = NULL;
        static $last_time = 0;

        if ($last_time == 0)
        {
            $last_time = time();
        }

        $now = time();

        if ($now >= $last_time + self::KEEPALIVE_TIME)
        {
            /* BEGIN: Modified by zhongjun 20130504 */
            /* 只是将$socket重置，应该存在发生资源泄漏的可能 */
            if (isset($socket))
            {
                fclose($socket);
                $socket = NULL;
            }
            /* BEGIN: Modified by zhongjun 20130504 */
        }

        if (is_null($socket))
        {
            $socket = fsockopen($this->httpsqs_host, $this->httpsqs_port, $errno, $errstr, 1);

            if (!$socket)
            {
                self::log("fsockopen failed in http_get");
                return false;
            }

            // 将socket读写超时设为10秒
            stream_set_timeout($socket, self::HTTPSQS_TIMEOUT);
        }

// 组装put消息
        $out = "POST ${query} HTTP/1.1\r\n";
        $out .= "Host: {$this->httpsqs_host}\r\n";
        $out .= "Content-Length: " . strlen($body) . "\r\n";
        $out .= "Connection: Keep-Alive\r\n";
        $out .= "Keep-Alive:".self::KEEPALIVE_TIME."\r\n";
        $out .= "\r\n";
        $out .= $body;

// 发送put命令
        $ret = fwrite($socket, $out);
        if ((false === $ret) || ($ret != strlen($out)))
        {
            // 发送get请求出错，或超时
            self::log("write GET request failed in http_post");

            fclose($socket);
            $socket = NULL;

            return false;
        }


        // 当请求发送成功时，更新$last_time
        $last_time = $now;
        $header    = '';
        $line = trim(fgets($socket));
        $header .= $line;
        list($proto, $rcode, $result) = explode(" ", $line);

        $len = -1;
        while (($line = trim(fgets($socket))) != "")
        {
            $header .= $line;
            if (strstr($line, "Content-Length:"))
            {
                list($cl, $len) = explode(" ", $line);
            }
            if (strstr($line, "Pos:"))
            {
                list($pos_key, $pos_value) = explode(" ", $line);
            }
            if (strstr($line, "Connection: close"))
            {
                $close = true;
            }
        }

        if ($len < 0)
        {
            // 接收应答消息的http头失败
            self::log("read http header failed in http_post");

            fclose($socket);
            $socket = NULL;

            return false;
        }

        $recv_body = @fread($socket, $len);

        if (strlen($recv_body) < $len)
        {
            // fread出错或超时
            self::log("read http body failed in http_post, the received body:\n$recv_body");
            fclose($socket);
            $socket = NULL;

            return false;
        }

        if ($close)
        {
            self::log("http_post: the CONNECTION option is CLOSE in http header");
            fclose($socket);
            $socket = NULL;
        }


        $result_array["pos"] = (int)$pos_value;
        $result_array["data"] = $recv_body;

        return $result_array;
    }



    public function put(&$queue_name, &$queue_data)
    {
        $tPostBegin = time();
        $result = $this->http_post("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=put", $queue_data);
        $tPostEnd = time();

        $tTime = $tPostEnd - $tPostBegin;
        if ($tTime > self::HTTPSQS_TIMEOUT)
        {
            self::log("httpsqs put takes $tTime seconds, and result is :".$result["data"]);
        }

        if ($result["data"] == "HTTPSQS_PUT_OK")
        {
            return true;
        }
        else if ($result["data"] == "HTTPSQS_PUT_END")
        {
            return $result["data"];
        }


        return false;
    }

    public function &get(&$queue_name)
    {
        $tGetBegin = time();
        $result = &$this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=get");
        $tGetEnd = time();

        $tTime = $tGetEnd - $tGetBegin;
        if ($tTime > self::HTTPSQS_TIMEOUT)
        {
            self::log("httpsqs get takes $tTime seconds, and result is :$result");
        }

        return $result["data"];
    }

    public function get_alive($queue_name)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=get");
        if ($result == false || $result["data"] == "HTTPSQS_ERROR" || $result["data"] == false) {
            return false;
        }
        return $result["data"];
        //       return $result;
    }


    public function gets($queue_name)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=get");
        if ($result == false || $result["data"] == "HTTPSQS_ERROR" || $result["data"] == false) {
            return false;
        }
        return $result;
    }

    public function status($queue_name)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=status");
        if ($result == false || $result["data"] == "HTTPSQS_ERROR" || $result["data"] == false) {
            return false;
        }
        return $result["data"];
    }

    public function view($queue_name, $queue_pos)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=view&pos=".$queue_pos);
        if ($result == false || $result["data"] == "HTTPSQS_ERROR" || $result["data"] == false) {
            return false;
        }
        return $result["data"];
    }

    public function reset($queue_name)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=reset");
        if ($result["data"] == "HTTPSQS_RESET_OK") {
            return true;
        }
        return false;
    }

    public function maxqueue($queue_name, $num)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=maxqueue&num=".$num);
        if ($result["data"] == "HTTPSQS_MAXQUEUE_OK") {
            return true;
        }
        return false;
    }

    public function status_json($queue_name)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=status_json");
        if ($result == false || $result["data"] == "HTTPSQS_ERROR" || $result["data"] == false) {
            return false;
        }
        return $result["data"];
    }


    public function synctime($num)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=httpsqs_synctime&opt=synctime&num=".$num);
        if ($result["data"] == "HTTPSQS_SYNCTIME_OK") {
            return true;
        }
        return false;
    }
}
