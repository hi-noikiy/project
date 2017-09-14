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
                        $playerId = $data->sheets[0]['cells'][$i][1];
                        $playerName = $data->sheets[0]['cells'][$i][2];
                        $serverId = $data->sheets[0]['cells'][$i][3];
                        $money = $data->sheets[0]['cells'][$i][4];
                        $goodList = $data->sheets[0]['cells'][$i][5];
//                        if(empty($playerId))
//                            continue;
//                        $yuanbao = $money*10;
//                        $rs = $this->gameInsert($serverId, $playerId, $yuanbao,$goodList);
//                        if($rs == false){
//                            echo "<script>alert('角色：".$playerName."发放物品失败！');</script>";
//                        }
                        $eMoney = $this->gameEMoney($serverId, $playerId);
                        if($eMoney == false)
                            continue;
                        write_log(ROOT_PATH."log","excel_emoney_log_", "$playerId,$playerName,RMB:$money,yuanbao:$eMoney, \r\n");
                    }
                }
                $this->display('倒入成功', 1);
            }catch (Exception $e){
               $this->display($e->getMessage(), 0);
            }
        }
        $this->render('excel');
    }
/*
    private function gameInsert($serverId, $playerId, $amount, $goodList){
        $table = subTable($serverId, 'u_gmtool', 1000);
        $sql = "insert into $table(type, serverid, param, message, award_type1, award_param1, award_amount1, award_type2, award_param2, award_amount2, award_type3, award_param3, award_amount3, award_type4, award_param4, award_amount4)";
        $sql .= " values(8, '$serverId', '$playerId' ,'补发双倍未领取', '7', '0', '$amount', '0', '0', '0', '0', '0', '0', '0', '0', '0');";
        $vipTable = subTable($serverId, 'u_player_vipshop', 1000);
        $vipSql = "insert into $vipTable(player_id,goodslist) values ('$playerId', '$goodList');";
        $conn = SetConn($serverId);
        if(@mysqli_query($conn, $vipSql)){
            if(mysqli_query($conn, $sql)){
                write_log(ROOT_PATH."log","excel_log_", "result=success,sql=$sql,vipSql=$vipSql, ".date("Y-m-d H:i:s")."\r\n");
            } else {
                write_log(ROOT_PATH."log","excel_log_fail_", "sql=$sql, \r\n");
            }
            return true;
        }
        write_log(ROOT_PATH."log","excel_log_fail_", "result=fail,sql=$sql,vipSql=$vipSql, ".date("Y-m-d H:i:s")."\r\n");
        return false;
    }
*/
    private function gameEMoney($serverId, $playerId){
        $conn = SetConn($serverId);
        if($conn == false)
            return false;
        $playerTable = subTable($serverId, 'u_player', 1000);
        $sql1 = "select account_id from $playerTable where id='$playerId' and serverid='$serverId'  limit 1";
        $query1 = @mysqli_query($conn,$sql1);
        $rs1 = @mysqli_fetch_assoc($query1);
        if(!isset($rs1['account_id']))
            return false;
        $accountId = $rs1['account_id'];
        //查询出钻石
        $playershareTable = subTable($serverId, 'u_playershare', 1000);
        $sql2 = "select emoney from $playershareTable where account_id='$accountId' and server_id='$serverId'  limit 1";

        $query2 = @mysqli_query($conn,$sql2);
        $rs2 = @mysqli_fetch_assoc($query2);
        if(isset($rs2['emoney']))
            return $rs2['emoney'];
        return false;
    }

}