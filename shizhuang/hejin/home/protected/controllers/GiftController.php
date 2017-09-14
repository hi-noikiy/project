<?php
/**
 * 2013-9-24 14:33
 * @author Administrator
 *
 */
class GiftController extends Controller {
    public function init() {
        parent::init();
        $this->title = '口袋妖怪VS礼包领取';
        $this->keyword = '口袋妖怪VS礼包领取';
        $this->desc = '口袋妖怪VS礼包领取';
    }

    public function actionGift(){
        $criteria = new CDbCriteria();
        $criteria->condition = 'status=0';
        $criteria->order = 'sort,id DESC';
        $criteria->group = 'used_type';
        $giftModel = Gift::model();
        $count = $giftModel->count($criteria);

        $pages = new CPagination($count);
        $pages->pageSize = 5;
        $pages->applyLimit($criteria);
        $criteria->select = 'id,name,`desc`';
        $giftList = $giftModel->findAll($criteria);

        $this->render('gift', array('pages'=>$pages,'giftList'=>$giftList, 'id'=>$this->action->id));
    }

    public function actionproblem(){
        $this->renderPartial('problem');
    }

    public function actionCheck(){
        $accountInfo = $this->getSession('accountInfo');
        if (!isset($accountInfo)) {
            exit(json_encode(array('status'=>0, 'msg'=>'error')));
        }else{
            if(!isset($_POST['codeType']))
                exit(json_encode(array('status'=>0, 'msg'=>'error')));
            $usedType = $_POST['codeType'];
            $accountId = $accountInfo['account_id'];
            $sql = "SELECT code_id FROM {{code_website}} WHERE account_id='$accountId' and used_type='$usedType' limit 1";
            $data = Yii::app()->db->createCommand($sql)->queryRow();
            if(empty($data)){
                $sql = "select code_id from {{code_website}} where account_id='0' and used_type='$usedType' order by id asc limit 1;";
                $data = Yii::app()->db->createCommand($sql)->queryRow();
                if($data){
                    $codeId = $data['code_id'];
                    $usedTime = time();
                    $sql = "update {{code_website}} set account_id='$accountId',used_time='$usedTime' where code_id='$codeId';";
                    Yii::app()->db->createCommand($sql)->execute();
                }
            }
            exit(json_encode(array('status'=>1, 'msg'=>'success', 'data'=>$data)));
        }
    }
}