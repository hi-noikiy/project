<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 
* ==============================================
* @date: 2016-10-11
* @author: luoxue
* @version:
*/
class CVogueActiveRecord extends CActiveRecord{
	public function getDbConnection(){
		return Yii::app()->db_vogue;
	}
}