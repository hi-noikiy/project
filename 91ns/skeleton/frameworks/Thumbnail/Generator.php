<?php

namespace Micro\Frameworks\Thumbnail;

use Phalcon\DI\FactoryDefault;
use PHPThumb\GD;

/**
 * A filesystem is used to store and retrieve files
 */
class Generator
{
    protected $di;
    protected $config;
    public $formatMapper;

    public function __construct()
    {   
        $this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');  
        $this->formatMapper = $this->generateFormatMapper();
    }

    public function getThumbnail($file, $profile)
    {
        //return $file;   //temp change
        if($this->config->application->debug)
        {
            return $file;   //temp change
            $info = pathinfo($file); 
            $dest = $info['dirname'].'/'.$info['filename'].'_'.$profile.'.jpg'; 

            $systemInfo = $this->di->get('storage')->getInfo();
            if($systemInfo['type'] == 'local')
            {
                $srcFile = $systemInfo['directory'].'/'.$file;
                $destFile = $systemInfo['directory'].'/'.$dest;

                $profileInfo = $this->getFormatInfo($profile);
                $thumb = new GD($srcFile);            
                $thumb->adaptiveResize($profileInfo['width'], $profileInfo['height']);
                $thumb->save($destFile, 'JPG');                
            }
            return $dest;
        }
        else
        {
            $dest = $file.'@!'.$profile;
            return $dest;
        }
    }  

    public function getPosterUrl($posterUrl = '', $avatar = ''){
        //初始化
        if (empty($posterUrl)) {        //不存在，取用户头像
            $posterUrl = $avatar;
            if (empty($posterUrl)) {// 头像不存在  取默认头像
                $posterUrl = $this->di->get('pathGenerator')->getFullDefaultAvatarPath();
            }
        }

        $posterArray = explode('?', $posterUrl);
        $posterUrl = $posterArray[0];
        // $posterUrls['poster'] = $posterUrl;
        // $posterUrls['small-poster'] = $posterUrl;
        // return $posterUrls;

        //如果是全路径
        if(substr($posterUrl, 0, 4) == 'http')
        {
            $posterUrls['poster'] = $posterUrl;

            if($this->config->application->debug) {
                $posterUrls['small-poster'] = $posterUrl;
            }
            else {
                $posterUrl = str_replace("cdn", "image", $posterUrl);
                $posterUrls['small-poster'] = $this->getThumbnail($posterUrl, 'poster-small');
            }
            
        }
        else {  //如果不是全路径
            $posterUrls['poster'] = $this->di->get('url')->getStatic($posterUrl);

            if($this->config->application->debug) {
                $posterUrls['poster'] = str_replace("//", "/", $posterUrls['poster']);    //兼容内网旧数据
                $posterUrl = $this->di->get('url')->getStatic($posterUrl);
                $posterUrl = str_replace("//", "/", $posterUrl);
                // $posterUrls['small-poster'] = $posterUrl;
                // $posterUrls['small-poster'] = $posterUrls['small-poster'];//str_replace("//", "/", $posterUrls['small-poster']);    //兼容内网旧数据
            }
            else {
                $posterUrl = $this->config->url->posterPre . $posterUrl;
            }
            $posterUrls['small-poster'] = $this->getThumbnail($posterUrl, 'poster-small');
        }

        return $posterUrls;
    }

    protected function generateFormatMapper()
    {
        //该名称需要跟oss中图片处理的样式的名字一致
        return array(
            'album-1x'     => array('width'=>110, 'height'=>74),
            'album-2x'     => array('width'=>240, 'height'=>174),
            'feed-1x'      => array('width'=>200, 'height'=>200),  
            'poster-small' => array('width'=>216, 'height'=>162), 
            'recommend-1x' => array('width'=>128, 'height'=>96)
        );
    } 

    protected function getFormatInfo($key)
    {
        if(!array_key_exists($key, $this->formatMapper))
        {
            throw new \Exception('profile of thumb is error!');
        }
        return $this->formatMapper[$key];
    }
}
