<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/20
 * Time: 下午3:39
 * 表字段权限控制
 */
class AccessTableController extends Controller{
    public function _condition(&$condition){
        $condition=array();
        if(isset($_POST['title']) && !empty($_POST['title']))
            $condition[]="title like '%{$_POST['title']}%'";
    }

    public function actionAdd(){
        $model = new AccessTable;
        if(isset($_POST['AccessTable'])){
            $model->attributes = $_POST['AccessTable'];
            $model->manager_access = implode(',', $_POST['access']);
            if($model->save())
                $this->success('添加信息成功');
            else
                $this->error('添加信息失败');
        }
        $this->render('add', array('model'=>$model, 'managerInfo'=>$this->getManagerInfo()));
    }

    public function actionUpdate($id){
        $model = AccessTable::model();
        $rs = $model->findByPk($id);
        if(isset($_POST['AccessTable'])){
            $rs->attributes = $_POST['AccessTable'];
            $rs->manager_access = implode(',', $_POST['access']);
            if($rs->save())
                $this->success('编辑信息成功');
            else
                $this->error('编辑信息失败');
        }
        $accessArr = isset($rs->manager_access) ? explode(',', $rs->manager_access) : [];

        $this->render('update', array('model'=>$rs,'accessArr'=>$accessArr,'managerInfo'=>$this->getManagerInfo()));
    }

    private function getManagerInfo(){
        $model = Manager::model();
        $info = $model->getAllManagerInfo();
        return $info;
    }
}