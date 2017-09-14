<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/14
 * Time: 下午3:12
 * 后台基本配置信息
 */
class ConfigController extends Controller{
    public function actionUpdate($id = 1){
        $model = Config::model();
        $rs = $model->findByPk($id);
        if(isset($_POST["Config"])){
            $rs->attributes=$_POST["Config"];
            if($rs->save())
                $this->success('更新信息成功');
            else
                $this->error(CHtml::errorSummary($rs));
        }
        $this->render('update', array('model'=>$rs));
    }
}
