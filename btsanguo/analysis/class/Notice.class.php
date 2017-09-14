<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-27
 * Time: 上午9:20
 * 系统公告
 */

/**
 * Class Notice 系统公告，向游服表写入公告数据
 */
class Notice {


    /**
     * 保存公告
     *
     * @param PDO $db
     * @param $msg              公告内容
     * @param $timeBegin        开始时间
     * @param $timeEnd          结束时间
     * @param $timeInterval     时间间隔
     * @param $createTimeMin    玩家起始注册时间
     * @param $createTimeMax    玩家结束注册时间
     * @param $lvlMin           玩家最小等级
     * @param $lvMax            玩家最大等级
     * @param $rmbMin           最小充值金额
     * @param $rmbMax           最大充值金额
     * @param array $fenbao     渠道
     * @param int   $type       类型
     * @param int   $noticeId   ID 大于0更新
     * @return mixed
     */
    private function Save(PDO $db, $msg, $timeBegin, $timeEnd, $timeInterval,
                                $createTimeMin, $createTimeMax, $lvlMin, $lvMax,
                                $rmbMin, $rmbMax,Array $fenbao,$type=100, $noticeId=0)
    {
        $db->exec("SET NAMES gb2312");
        if ($noticeId>0) {
            $sql = "UPDATE u_gmtool SET `message`='$msg',`time_begin`=$timeBegin,"
                . "`time_end`=$timeEnd,`time_dis`=$timeInterval,"
                . "`createtime_min`=$createTimeMin,`createtime_max`=$createTimeMax,"
                . "`level_min`=$lvlMin, `level_max`=$lvMax, `rmb_min`=$rmbMin,"
                . "`rmb_max`=$rmbMax WHERE id=$noticeId";
        }
        else {
            $sql = "INSERT INTO u_gmtool(`type`, `message`, `status`, `time_begin`,"
                ." `time_end`, `time_dis`, `createtime_min`, `createtime_max`, "
                ."`level_min`, `level_max`, `rmb_min`, `rmb_max`, `fenbao`) VALUES";

            foreach( $fenbao as $fb) {
                $sql .= "($type,'$msg',0, $timeBegin,$timeEnd,$timeInterval,$createTimeMin,$createTimeMax,$lvlMin,$lvMax, $rmbMin,$rmbMax,$fb),";
            }
            $sql = rtrim($sql, ',');
        }

        return $db->exec($sql);
    }
    public function delete($db = null)
    {
        $serverId = intval($_POST['serverId']);
        $db = is_null($db) ? db($serverId) : $db;
        $noticeId = intval($_POST['noticeId']);
        $sql = "DELETE FROM u_gmtool WHERE id=$noticeId";
//        echo $sql;
        $ret = $db->exec($sql);
        if ($ret===false) {
            return array('status'=>'fail', 'msg'=>'删除失败。原因:'.$db->errorInfo());
        }
        return array('status'=>'ok', 'msg'=>'删除成功');
    }
    public function update($db=null)
    {
        $serverId = intval($_POST['serverId']);
        if ( ! $serverId ) {
            return array('status'=>'fail', 'msg'=>'区服ID为空！');
        }

        $msg = trim($_POST['msg']);

        if (!strlen($msg)) {
            return array('status'=>'fail', 'msg'=>'公告内容不能为空');
        }
        $msg = mb_convert_encoding($msg, 'gb2312', 'utf-8');
//        echo $msg;exit;
        $timeBegin = $this->formatDateTime($_POST['timeBegin']);
        $timeEnd   = $this->formatDateTime($_POST['timeEnd']);
        $timeInterval = intval($_POST['timeInterval']);
        $createTimeMin = $this->formatDateTime($_POST['createTimeMin']);
        $createTimeMax = $this->formatDateTime($_POST['createTimeMax']);
        $lvlMin = intval($_POST['lvlMin']);
        $lvMax  = intval($_POST['lvlMax']);
        $rmbMin = intval($_POST['rmbMin']);
        $rmbMax = intval($_POST['rmbMax']);
        $db = is_null($db) ? db($serverId) : $db;
        $ret = self::Save($db, $msg, $timeBegin, $timeEnd, $timeInterval,
            $createTimeMin, $createTimeMax, $lvlMin, $lvMax,$rmbMin, $rmbMax,
            array(), null, intval($_POST['noticeId']));
        if ($ret===false) {
            $errMsg = "公告更新失败。区服ID{$serverId},失败原因:" . $db->errorInfo();
            writeLog($errMsg);
            $errMsgArr[] = $errMsg;
        }
        else {
            $sucMsg = "更新公告成功。公告条数" . $ret;
            writeLog($sucMsg);
        }
    }
    public function Show($serverId, $noticeId, $db = null)
    {
        try {
            $db = is_null($db) ? db($serverId) : $db;
            $sql = "SELECT * FROM u_gmtool WHERE id=?";
            $stmt = $db->prepare($sql);
            $stmt->execute(array($noticeId));
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
        }


    }

    public function add()
    {
        $serveridList = $_POST['serverid'];

        if (count($serveridList)) {
            $msg = trim($_POST['msg']);

            if (!strlen($msg)) {
                return array('status'=>'fail', 'msg'=>'公告内容不能为空');
            }
            $msg = mb_convert_encoding($msg, 'gb2312', 'utf-8');
//        echo $msg;exit;
            $timeBegin = $this->formatDateTime($_POST['timeBegin']);
            $timeEnd   = $this->formatDateTime($_POST['timeEnd']);
            $timeInterval = intval($_POST['timeInterval']);
            $createTimeMin = $this->formatDateTime($_POST['createTimeMin']);
            $createTimeMax = $this->formatDateTime($_POST['createTimeMax']);
            $lvlMin = intval($_POST['lvlMin']);
            $lvMax  = intval($_POST['lvlMax']);
            $rmbMin = intval($_POST['rmbMin']);
            $rmbMax = intval($_POST['rmbMax']);
            $fenbao = $_POST['fenbaoids'];
            $errMsgArr = array();
            foreach ($serveridList as $serverid) {
                $db = db($serverid);
//                $db = db('analysis');
                $ret = $this->Save($db, $msg, $timeBegin, $timeEnd, $timeInterval,
                    $createTimeMin, $createTimeMax, $lvlMin, $lvMax, $rmbMin,
                    $rmbMax,$fenbao);
                if ($ret===false) {
                    $errMsg = "公告发布失败。区服ID{$serverid},失败原因:" . $db->errorInfo();
                    writeLog('fail|'.$errMsg);
                    $errMsgArr[] = $errMsg;
                }
                else {
                    writeLog("ok|发布公告成功。公告条数:$ret" );
                }
            }
            if (count($errMsgArr)) {
                $msg = implode('<br/>', $errMsgArr);
            }
            else {
                $msg = '发送成功';
            }
            return array('status'=>'ok', 'msg'=>$msg);
        }
    }

    /**
     * 格式化时间
     *
     * @param $dateTime
     * @param string $format
     * @return bool|string
     */
    public function formatDateTime($dateTime, $format='ymdHi')
    {
        if ($dateTime<200000000000) {
            $dateTime = '20' . $dateTime;
        }
        return date($format, strtotime($dateTime));
    }
    /**
     * 公告列表
     *
     * @return array
     */
    public function NoticeList($db)
    {
//        $db     = db(intval($_GET['serverid']));
//        $db     = db(intval($_GET['serverid']));
        $db->exec("SET NAMES gb2312");
        $sql    = "SELECT * FROM u_gmtool";
        $where  = ' WHERE 1=1';
        $fenbaoid = $_GET['fenbaoids'];
        $limit   = isset($_GET['currentPage']) ? intval($_GET['currentPage']) : 0;
        $offset  = isset($_GET['pageSize']) ? intval($_GET['pageSize']) : 20;
        if (!empty($_GET['bt'])) {
            $where .= " AND time_begin>=" . $this->formatDateTime($_GET['bt']);
        }
        if (!empty($_GET['et'])) {
            $where .= " AND time_end<" . $this->formatDateTime($_GET['et']);
        }
        if (count($fenbaoid)>0) {
            $where .= " AND fenbao IN(".implode(',', $fenbaoid).")";
        }
        $sqlTotal = "SELECT COUNT(*) FROM u_gmtool" . $where;
//        echo $sqlTotal;
        $stmt = $db->prepare($sqlTotal);
        $stmt->execute();
        $total = $stmt->fetchColumn(0);
        $list = array();
        if ($total>0) {
            $sql = $sql.$where ." ORDER BY id DESC LIMIT $limit, $offset";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return array('total'=>$total, 'list'=>$list);
    }
} 