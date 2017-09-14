<?php

namespace Micro\Frameworks\Path;

use Phalcon\DI\FactoryDefault;

class Generator
{
    protected $di;
    protected $config;
    protected $url;

    public function __construct()
    {   
        $this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
        $this->url = $this->di->get('url');
    }

    public function getFullDefaultAvatarPath() {
        return $this->getFullAvatarPath('default', '0.jpg');
    }

    public function getAvatarPath($uid) {
        return $this->config->websiteinfo->useruploadpath.$uid.$this->config->websiteinfo->avatarpath;
    }

    public function getFullAvatarPath($uid, $filename) {
        $filepath = $this->getAvatarPath($uid).$filename;
        return $this->url->getStatic($filepath);
    }

    public function getFullMessagePath($uid, $filename) {
        $filepath = $this->getMessagePath($uid).$filename;
        return $this->url->getStatic($filepath);
    }

    public function getMessagePath($uid) {
        return $this->config->websiteinfo->useruploadpath.$uid.$this->config->websiteinfo->messagepath;
    }

    public function getCustomDefaultAvatarPath() {
        $filepath = $this->config->websiteinfo->useruploadpath.'default'.$this->config->websiteinfo->avatarpath;
        return $this->url->getStatic($filepath);
    }

    public function getPosterPath($uid) {
        return $this->config->websiteinfo->useruploadpath.$uid.$this->config->websiteinfo->posterpath;
    }

    public function getFullPosterPath($uid, $filename) {
        $filepath = $this->getPosterPath($uid).$filename;
        return $this->url->getStatic($filepath);
    }

    public function getRelPosterPath($uid, $filename) {//相对路径
        $filepath = $this->getPosterPath($uid).$filename;
        return $filepath;
    }

    public function getLivePicPath($uid) {
        return $this->config->websiteinfo->useruploadpath.$uid.$this->config->websiteinfo->livepicpath;
    }

    public function getFullLivePicPath($uid, $filename) {
        $filepath = $this->getLivePicPath($uid).$filename;
        return $this->url->getStatic($filepath);
    }

    public function getAlbumPath($uid) {
        return $this->config->websiteinfo->useruploadpath.$uid.$this->config->websiteinfo->albumpath;
    }

    public function getFullAlbumPath($uid, $filename) {
        $filepath = $this->getAlbumPath($uid).$filename;
        return $this->url->getStatic($filepath);
    }

    public function getMovementPath($uid) {
        return $this->config->websiteinfo->useruploadpath.$uid.$this->config->websiteinfo->movementpath;
    }

    public function getFullMovementPath($uid, $filename) {
        $filepath = $this->getMovementPath($uid).$filename;
        return $this->url->getStatic($filepath);
    }

    public function getFamilyPosterPath($uid) {
        return $this->config->websiteinfo->useruploadpath.$uid.$this->config->websiteinfo->familyposterpath;
    }

    public function getFullFamilyPosterPath($uid, $filename) {
        $filepath = $this->getFamilyPosterPath($uid).$filename;
        return $this->url->getStatic($filepath);
    }

    public function getRelFamilyPosterPath($uid, $filename) {//相对路径
        $filepath = $this->getFamilyPosterPath($uid).$filename;
        return $filepath;
    }
    
    
    //客服后台上传账单路径
    public function getInvAccountPath($date) {
        return $this->config->websiteinfo->invuploadpath .$date . $this->config->websiteinfo->accountpath;
    }
    
    public function getFullInvAccountPath($date, $filename) {
        $filepath = $this->getInvAccountPath($date) . $filename;
        return $this->url->getStatic($filepath);
    }

    public function getFullSuggestionsPath($dirName, $filename, $sugType, $kind) {
        $filepath = $this->getSuggestionsPath($dirName, $sugType, $kind) . $filename;
        return $this->url->getStatic($filepath);
    }

    public function getSuggestionsPath($dirName, $sugType, $kind) {
        $path = ($kind == 'sug' ? $this->config->websiteinfo->suggestionpath : $this->config->websiteinfo->informpath);
        return $this->config->websiteinfo->useruploadpath . $path . $sugType . '/' . $dirName . '/';
    }

    public function getChatDataPath($dirName) {
        return $this->config->websiteinfo->chatdatapath . $dirName . '/';
    }

    public function getFullDynamicsPath($dirName, $filename) {
        $filepath = $this->getDynamicsPath($dirName) . $filename;
        return $this->url->getStatic($filepath);
    }

    public function getDynamicsPath($dirName) {
        return $this->config->websiteinfo->useruploadpath . $this->config->websiteinfo->dynamicspath . $dirName . '/';
    }

    public function getFullFamilySkinPath($uid, $dirName, $filename) {
        $filepath = $this->getFamilySkinPath($uid, $dirName) . $filename;
        return $this->url->getStatic($filepath);
    }

    public function getFamilySkinPath($uid, $dirName) {
        return $this->config->websiteinfo->useruploadpath  . $uid . $this->config->websiteinfo->familyskinpath . $dirName . '/';
    }

    public function getFullFamilyLogoPath($uid, $dirName, $filename) {
        $filepath = $this->getFamilyLogoPath($uid, $dirName) . $filename;
        return $this->url->getStatic($filepath);
    }

    public function getFamilyLogoPath($uid, $dirName) {
        return $this->config->websiteinfo->useruploadpath . $uid . $this->config->websiteinfo->familylogopath . $dirName . '/';
    }

    public function getFullSuggestionsLogPath($filename) {
        $filepath = $this->getSuggestionsLogPath() . $filename;
        return $this->url->getStatic($filepath);
    }

    public function getSuggestionsLogPath() {
        return $this->config->websiteinfo->useruploadpath . $this->config->websiteinfo->suggestionlogpath;
    }

    public function getFullSyncGa($filename) {
        $filepath = $this->getSyncGa() . $filename;
        return $this->url->getStatic($filepath);
    }

    public function getSyncGa() {
        return $this->config->websiteinfo->gapath;
    }
    
    //推广活动生成推荐链接的二维码 路径
    public function getRecommendqrcodePath($filename) {
        $filepath =$this->config->websiteinfo->recommendqrcodepath . $filename;
        return $filepath;
    }

    //推广活动生成推荐链接的二维码全路径
    public function getFullRecommendqrcodePath($filename) {
        $filepath = $this->getRecommendqrcodePath($filename);
        return $this->url->getStatic($filepath);
    }
    //主播海报
    public function getAnchorPosterPath($uid) {
        return $this->config->websiteinfo->useruploadpath . $uid . $this->config->websiteinfo->anchorposterpath;
    }
    
    public function getFullAnchorPosterPath($uid, $filename) {
        $filepath = $this->getAnchorPosterPath($uid).$filename;
        return $this->url->getStatic($filepath);
    }

    //主播相册
    public function getAnchorAlbumPath($uid) {
        return $this->config->websiteinfo->useruploadpath . $uid . $this->config->websiteinfo->anchoralbumpath;
    }
    
    public function getFullAnchorAlbumPath($uid, $filename) {
        $filepath = $this->getAnchorAlbumPath($uid).$filename;
        return $this->url->getStatic($filepath);
    }

}