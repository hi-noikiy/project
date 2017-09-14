<?php

namespace Interfaces\Model;
use Think\Model;

class FilmModel extends Model {
	function getList($field,$map=array(),$limit = '',$order='id'){
		$filmsList = M('Film')->field($field)->where($map)->limit($limit)->order($order)->select();
		return $filmsList ? $filmsList : array();
	}
	function getFilm($map){
		$film=M('Film')->where($map)->find();
		if(!empty($film)){
			$imgs=explode(';', substr($film['imgs'], 0,-1));
			if(!empty($film['image'])){
				$film['image']=C('IMG_URL').'Uploads/'.$film['image'];
			}else{
				$film['image']=C('FILM_IMG_URL');
			}
			if(!empty($film['prevsImg'])){
				$film['prevsImg']=C('IMG_URL').'Uploads/'.$film['prevsImg'];
			}else{
				$film['prevsImg']=C('FILM_IMG_URL');
			}
			unset($film['imgs']);
			foreach ($imgs as $k=>$v){
				$film['imgs'][$k]=C('IMG_URL').'Uploads/'.$v;
			}
		}
		return $film;
	}
	function countFilm($map){
		return M('Film')->where($map)->count();
	}
	/**
	 * 评论列表
	 * @param unknown $map
	 * @return \Think\mixed
	 */
	function getViews($map,$start=0,$limit=9999999){
		$views=M('filmView')->where($map)->order('time desc')->limit($start,$limit)->select();
		foreach ($views as $k=>$v){
			$mem[$k]=M('member')->find($v['uid']);
			if(!empty($mem[$k]['headImage'])){
				$views[$k]['headImage']=C('IMG_URL').'Uploads/'.$mem[$k]['headImage'];
			}else{
				$views[$k]['headImage']=C('HEAD_IMG_URL');
			}
			
			$views[$k]['otherName']=$mem[$k]['otherName'];
		}
		return $views;
	}
	
	/**
	 * 我的评论列表
	 * @param unknown $map
	 * @return \Think\mixed
	 */
	function getMyViews($map,$start=0,$limit=999999){
		$views=M('filmView')->where($map)->order('time desc')->limit($start,$limit)->select();
		foreach ($views as $k=>$v){
			$film[$k]=M('film')->find($v['filmId']);
			$views[$k]['image']=C('IMG_URL').'Uploads/'.$film[$k]['image'];
			$views[$k]['simpleword']=$film[$k]['simpleword'];
			$views[$k]['type']=$film[$k]['type'];
			$views[$k]['version']=$film[$k]['version'];
			$views[$k]['time']=date('Y-m-d H:i',$v['time']);
		}
		return $views;
	}
	/**
	 * 添加评论
	 * @param unknown $data
	 * @return \Think\mixed
	 */
	function addView($data){
		$result= M('filmView')->add($data);
		if(!empty($result)){
			if(!empty($data['pid'])){
				$view=M('filmView')->find($data['pid']);
				M('filmView')->where(array('id'=>$data['pid']))->setInc('lookNum',1);
			}
		}
		return $result;
	}

}