<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/3
 * Time: 下午3:54
 */
class WelfareController extends CommonController{
    public function _condition(&$condition){
        $condition=array();
        if(isset($_POST['realName']) && !empty($_POST['realName']))
            $condition[]="real_name like '%{$_POST['realName']}%'";
    }



    public function actionAdd(){
        $model = new Welfare;
        $game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
        $gameServer = GameServer::model()->getInfo();
        $gameId = isset($_POST['gameid']) ? intval($_POST['gameid']) : $this->mangerInfo['game_id'];
        $serverId = isset($_POST['serverid']) ? intval($_POST['serverid']) : 0;
        $payDate = date('Ym',time());
        if(!empty($_POST['Welfare'])){
            $playerName = $_POST['Welfare']['player_name'];
            $palyerInfo = $this->checkPlayer($playerName, 0 , $serverId);
            if($palyerInfo == false)
                $this->error('角色不存在！');

            $model->attributes=$_POST["Welfare"];
            $model->player_id = $palyerInfo['id'];
            $model->server_id = $serverId;
            if($model->save())
                $this->success('添加信息成功');
            else
                $this->error('添加信息失败');
        }
        $this->renderPartial('add', array('model'=>$model,'game'=>$game,'gameServer'=>$gameServer,'gameId' => $gameId,'serverId' => $serverId, 'payDate'=>$payDate));
    }

    public function actionUpdate($id){
        $model = Welfare::model();
        $rs = $model->findByPk($id);
        $game = Game::model()->getGame($this->mangerInfo['game_id'], '游戏');
        $gameServer = GameServer::model()->getInfo();
        $serverId = $rs->server_id;

        if(!empty($_POST['Welfare'])){
            $playerName = $_POST['Welfare']['player_name'];
            $palyerInfo = $this->checkPlayer($playerName, 0 , $serverId);
            if($palyerInfo == false)
                $this->error('角色不存在！');

            $rs->attributes=$_POST["Welfare"];
            $rs->player_id = $palyerInfo['id'];
            $rs->server_id = $serverId;
            if($rs->save())
                $this->success('更新信息成功');
            else
                $this->error('更新信息失败');
        }
        $this->renderPartial('update', array('model'=>$rs,'game'=>$game,'gameServer'=>$gameServer,'gameId' => $this->mangerInfo['game_id'],'serverId' => $rs->server_id));
    }


    public function actionExcel(){
        if(isset($_FILES['attachment'])){
            $payModel = PayLog::model();

            $depArr = array(
                '2'=>'总办','6'=>'程序部','7'=>'策划部','8'=>'美术部',
                '9'=>'QA部','10'=>'运营部','11'=>'客服部','12'=>'总办',
                '13'=>'程序部','14'=>'iphone','15'=>'AS3','16'=>'市场部'
            );

            try{
                $file=CUploadedFile::getInstanceByName('attachment');
                if(is_object($file) && get_class($file) === 'CUploadedFile'){
                    // 判断实例化是否成功
                    if($file->extensionName!='xls')
                        $this->display('上传格式不正确', 0);
                    $date=date("Ym",time());
                    $fileName = $this->uploadPath.'/record';
                    if(!file_exists($fileName))
                        mkdir($fileName,0777);
                    $fileName .='/'.$date;
                    if(!file_exists($fileName))
                        mkdir($fileName,0777);
                    $attachment = $fileName.'/'.$file->getName();   //定义文件保存的名称
                    if(!$file->saveAs($attachment))
                        $this->display('文件上传失败', 0);
                    //=======
                    Yii::import('ext.excel.Spreadsheet_Excel_Reader');
                    $data=new Spreadsheet_Excel_Reader();
                    $data->setOutputEncoding('utf-8');
                    $data->read($attachment);
                    for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
                        if($i%2 != 0)
                            continue;
                        if(!isset($data->sheets[0]['cells'][$i+1]))
                            break;
                        $gInfo = $data->sheets[0]['cells'][$i];
                        $gongId = isset($gInfo[3]) ? $gInfo[3] : 0;
                        $name = isset($gInfo[26]) ? $gInfo[26] : '';
                        if(!$name)
                            continue;
                        if($name !='陈祥坤')
                            continue;
                        $sql = "select card_id,depId from _sys_admin where real_name='$name'";
                        $connection = Yii::app()->db;
                        $command = $connection->createCommand($sql);
                        $result = $command->queryRow();
                        if(!empty($result)){
                            $cardId = $result['card_id'];
                            $depName = $depArr[$result['depId']];
                            $exInfo = $data->sheets[0]['cells'][$i+1];
                            foreach ($exInfo as $k => $v){
                                $v = str_replace("\n",'', $v);
                                $date = date('Y-m').'-'.$k;
                                $ex = implode(str_split($v, 5), ';');

                                $sql = "insert into _web_record (id,card_id,gong_id,name,depname,addtime,descri,recorddate,addtime_ex)";
                                $sql .= " VALUES ('','$cardId','$gongId','$name','$depName','无门禁记录','允许通过','$date','$ex')";

                                write_log(ROOT_PATH . "log", "record_log_", "$sql;\r\n");
                            }
                        }
                        //break;


//                        $orderId = $data->sheets[0]['cells'][$i][2];
//                        $orderId = str_replace('OR:', '', $orderId);
//                        $sql = "select id,Add_Time from {{pay_log}} where OrderID='$orderId' limit 1;";
//                        $info = $payModel->findBySql($sql);
//                        if(!isset($info->id)){
//                            print_r($orderId);
//                            echo '<br>';
//                        } else {
//                            if(strtotime($info->Add_Time)>strtotime('2017-03-31 23:59:59')){
//                                print_r($i.'=='.$orderId.'==='.$info->Add_Time);
//                                echo '<br>';
//                            } else if(strtotime($info->Add_Time)<strtotime('2017-03-01 00:00:00')){
//                                print_r($i.'=='.$orderId.'==='.$info->Add_Time);
//                                echo '<br>';
//                            }
//                        }

                    }
                }
                exit();
                //$this->display('倒入成功', 1);
            }catch (Exception $e){
               $this->display($e->getMessage(), 0);
            }
        }
        $this->render('excel');
    }




}