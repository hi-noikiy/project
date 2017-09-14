<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/16
 * Time: 下午2:48
 */
class ServiceController extends Controller{
    public function init() {
        parent::init();
        $this->title = '玩家服务中心';
        $this->keyword = '玩家服务中心';
        $this->desc = '玩家服务中心';
    }

    public function actionService(){

        $this->render('service', array('id'=>$this->action->id));
    }

    public function actionProblem(){
        $this->layout = 'column1';
        $gameServer = $this->getServer();
        if (isset($_POST['btn'])) {
            $phone = $_POST['phone'];
            $server_id = $_POST['server_id'];
            $username = $_POST['username'];
            $desc = $_POST['desc'];
            $model = $_POST['model'];
            $system = $_POST['system'];
            $operate = $_POST['operate'];
            $image = '';
            $file = CUploadedFile::getInstanceByName("image");
            if(is_object($file) && get_class($file) === 'CUploadedFile'){
                $imageArr = array('jpg', 'jpeg', 'gif', 'png');
                if(!in_array($file->extensionName, $imageArr)){
                    echo '<script>alert("图片格式不对")</script>';
                    return false;
                }
                if($file->size > 2*1024*1024){
                    echo '<script>alert("文件大小不能超过2M")</script>';
                    return false;
                }
                // 判断实例化是否成功
                $path = Yii::app()->params['uploadPath'];
 				$date = date("Ymd",time());
 				$filename = $path.'/problem/'.$date;
 				if(!file_exists($filename))
 					@mkdir($filename, 0777);
 				$image = $filename.'/'.time().'_'.rand(0,9999).'.'.$file->extensionName;   //定义文件保存的名称
 			}
            $addtime = time();
            $sql = "insert into {{problem}} (phone,server_id,username,`desc`,model,system,operate,image,addtime)";
            $sql .= " values('$phone', '$server_id', '$username', '$desc', '$model','$system', '$operate', '$image','$addtime')";
            $count = Yii::app()->db->createCommand($sql)->execute();
            if($count) {
                if(is_object($file) && get_class($file) === 'CUploadedFile')
 					@$file->saveAs($image);// 上传图片
                echo '<script>alert("问题反馈成功，等待客服反馈，感谢支持！");window.location.href="/"</script>';
            }
        }
        $this->render('problem',array( 'gameServer' => $gameServer,array('id'=>$this->action->id)));
    }

    public function actionSuggest(){
        $this->layout = 'column1';
        $gameServer = $this->getServer();
        if (isset($_POST['btn'])) {
            $phone = $_POST['phone'];
            $server_id = $_POST['server_id'];
            $username = $_POST['username'];
            $desc = $_POST['desc'];
            $image = '';
            $file = CUploadedFile::getInstanceByName("image");
            if(is_object($file) && get_class($file) === 'CUploadedFile'){
                $imageArr = array('jpg', 'jpeg', 'gif', 'png');
                if(!in_array($file->extensionName, $imageArr)){
                    echo '<script>alert("图片格式不对")</script>';
                    return false;
                }
                if($file->size > 2*1024*1024){
                    echo '<script>alert("文件大小不能超过2M")</script>';
                    return false;
                }
                // 判断实例化是否成功
                $path = Yii::app()->params['uploadPath'];
                $date = date("Ymd",time());
                $filename = $path.'/problem/'.$date;
                if(!file_exists($filename))
                    @mkdir($filename, 0777);
                $image = $filename.'/'.time().'_'.rand(0,9999).'.'.$file->extensionName;   //定义文件保存的名称
            }
            $sql = "insert into {{problem}} (phone,type,server_id, username,`desc`, image)";
            $sql .= " values('$phone','1','$server_id','$username','$desc','$image')";
            $count = Yii::app()->db->createCommand($sql)->execute();
            if($count){
                if(is_object($file) && get_class($file) === 'CUploadedFile')
                    @$file->saveAs($image);// 上传图片
                echo '<script>alert("意见反馈成功，等待客服反馈，感谢支持！");window.location.href="/"</script>';
            }
        }
        $this->render('suggest',array( 'gameServer' => $gameServer, 'id'=>$this->action->id));
    }

}