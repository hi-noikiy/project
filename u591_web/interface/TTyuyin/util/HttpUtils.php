<?php

class HttpUtils {
    /**
     * 执行一个 HTTP 请求
     *
     * @param string 	$Url 执行请求的Url
     * @param mixed	$Params 表单参数
     * @param string	$Method 请求方法 post / get
     * @return array 结果数组
     */
    public static function http_post_data($url, $urldata, $headers) {
        $TIMEOUT = 30; //超时时间(秒)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $TIMEOUT);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $TIMEOUT - 2);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $urldata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();
        ob_end_clean();

        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return array(
            'code' => $return_code,
            'msg' => $return_content
        );
    }

}

?>
