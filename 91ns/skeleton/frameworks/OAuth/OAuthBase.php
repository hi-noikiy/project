<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-2 上午1:00
 *
 */
namespace Micro\Frameworks\OAuth;

class OAuthBase 
{
    protected $clientId;
    protected $clientSecret;
    protected $callback;
    protected $type;

    protected $state;
    protected $accessToken;
    protected $refreshToken;
    protected $openId;

    public function request($url, $params = array())
    {
        return $this->get($url, $params);
 
        $ch = curl_init();     
        $curl_opts = array(
            CURLOPT_CONNECTTIMEOUT  => 3,
            CURLOPT_TIMEOUT         => 5,
            CURLOPT_USERAGENT       => '91ns-php',
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HEADER          => false,
            CURLOPT_FOLLOWLOCATION  => false,
        );
        if (stripos($url, 'https://') === 0) {
            $curl_opts[CURLOPT_SSL_VERIFYPEER] = false;
        }   

        $query = http_build_query($params, '', '&');
        $delimiter = strpos($url, '?') === false ? '?' : '&';
        $curl_opts[CURLOPT_URL] = $url . $delimiter . $query;
        $curl_opts[CURLOPT_POST] = false;


        curl_setopt_array($ch, $curl_opts);
        $result = curl_exec($ch);

        if ($result === false) {
            curl_close($ch);
            return false;
        } 
        else if (empty($result)) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_code != 200) {
                curl_close($ch);
                return false;
            }
        }  
        curl_close($ch);
        return $result;
    } 

    public function get($url, $parameters = array()) {
        $response = $this->oAuthRequest($url, 'GET', $parameters);
        return $response;
    }

    public function post($url, $parameters = array(), $multi = false) {
        $response = $this->oAuthRequest($url, 'POST', $parameters, $multi );
        return $response;
    }

    private $http_info;
    private $useragent = 'Sae T OAuth2 v0.1';
    private $connecttimeout = 30;
    private $timeout = 30;
    private $ssl_verifypeer = FALSE;

    public function oAuthRequest($url, $method, $parameters, $multi = false) {

        if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
                $url = "{$this->host}{$url}.{$this->format}";
        }

        switch ($method) {
            case 'GET':
                $url = $url . '?' . http_build_query($parameters);
                return $this->http($url, 'GET');
            default:
                $headers = array();
                if (!$multi && (is_array($parameters) || is_object($parameters)) ) {
                    $body = http_build_query($parameters);
                } else {
                    $body = self::build_http_query_multi($parameters);
                    $headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
                }
            return $this->http($url, $method, $body, $headers);
        }
    }


    function getHeader($ch, $header) {
        $i = strpos($header, ':');
        if (!empty($i)) {
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
            $value = trim(substr($header, $i + 2));
            $this->http_header[$key] = $value;
        }
        return strlen($header);
    }

    public function http($url, $method, $postfields = NULL, $headers = array()) {
        $this->http_info = array();
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_ENCODING, "");
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
        if (version_compare(phpversion(), '5.4.0', '<')) {
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 1);
        } else {
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 2);
        }
        curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
        curl_setopt($ci, CURLOPT_HEADER, FALSE);

        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, TRUE);
                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                }
                break;
            case 'DELETE':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($postfields)) {
                    $url = "{$url}?{$postfields}";
                }
        }

        if ( isset($this->accessToken) && $this->accessToken )
            $headers[] = "Authorization: OAuth2 ".$this->accessToken;

        if ( !empty($this->remote_ip) ) {
            if ( defined('SAE_ACCESSKEY') ) {
                $headers[] = "SaeRemoteIP: " . $this->remote_ip;
            } else {
                $headers[] = "API-RemoteIP: " . $this->remote_ip;
            }
        } else {
            if ( !defined('SAE_ACCESSKEY') ) {
                $headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
            }
        }
        curl_setopt($ci, CURLOPT_URL, $url );
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );

        $response = curl_exec($ci);
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
        $this->url = $url;

        curl_close ($ci);
        return $response;
    }


    public function isStateValid($state)
    {
        $valid = $this->state;
        return $state == $valid;  
    }
}
