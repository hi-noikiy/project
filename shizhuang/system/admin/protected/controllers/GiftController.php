<?php
class GiftController extends Controller{

    public function _condition(&$condition){
        $condition = array();
        if (isset($_POST['name']) && !empty($_POST['name']))
            $condition[] = "name like '{$_POST['name']}%'";
        if (isset($_POST['used_type']) && !empty($_POST['used_type']))
            $condition[] = "used_type='{$_POST['used_type']}'";
    }

    public function _order(&$order){
        $order = 'sort,id desc';
    }

    public function _params(&$params){
        $params = array();
        $usedTypeList = CodeWebsite::model()->getUsedType();
        $params['usedTypeList'] = $usedTypeList;
    }

    public function actionUpload(){
        if(!empty($_POST)){
            if(!isset($_FILES['attachment']))
                $this->display('请选择上传文件', 0);
            $usedType = intval($_POST['used_type']);
            if(!$usedType)
                $this->display('批次错误', 0);
            $rs = $this->fileImport('attachment');
            if($rs['status'] != 0)
                $this->display($rs['msg'], 0);
            $path = $rs['data']['uploadPath'];
            if(!file_exists($path))
                $this->display('读取文件异常', 0);

            $str = file_get_contents($path);
            //将整个文件内容读入到一个字符串中
            $dataArr = explode("\r\n", $str);
            if(!empty($dataArr)){
                $db = Yii::app()->db;
                foreach ($dataArr as $v){
                    $sql = "insert into web_code_website (code_id, used_type) values('$v', '$usedType')";
                    $db->createCommand($sql)->execute();
                }
            }
            $this->display('激活码倒入成功', 1);
        }
        $this->render('upload');
    }
    private function fileImport($name){
        try{
            $file=CUploadedFile::getInstanceByName($name);
            if(is_object($file) && get_class($file) === 'CUploadedFile') {
                // 判断实例化是否成功
                if($file->extensionName != 'txt')
                    return array('status'=>1, 'msg'=>'格式必须是文件格式');
                $uploadPath = $this->uploadPath.'/code';
                if(!is_dir($uploadPath))
                    @mkdir($uploadPath, 0777);
                $attachment = $uploadPath.'/'.$file->getName();
                //定义文件保存的名称
                if(!$file->saveAs($attachment))
                    return array('status'=>1, 'mgs'=>'文件上传失败');
                return array('status'=>0, 'msg'=>'success', 'data'=>array('uploadPath'=>$attachment));
            }
        }catch (Exception $e){
            $msg = $e->getMessage();
            return array('status'=>1, 'msg'=>"$msg");
        }
    }

}
