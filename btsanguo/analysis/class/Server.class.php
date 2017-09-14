<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-11
 * Time: ????9:35
 */

class Server {
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function Show($limit=0, $offset=20)
    {
        $sql_cnt = "SELECT COUNT(*) FROM servers_list";
        $stmt = $this->db->prepare($sql_cnt);
        $stmt->execute();
        $total = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $sql = "SELECT * FROM servers_list LIMIT ?, ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($limit, $offset));
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array('total'=>$total, 'list'=>$list);
    }

    public function Create()
    {
        $serverId   = intval($_POST['serverid']);
        $serverName = trim($_POST['serverName']);
        $opentime   = trim($_POST['opentime']);
        $gameid     = intval($_POST['gameid']);
        $groupid    = intval($_POST['groupid']);

        $insert = "INSERT INTO servers_list(gameid,groupid,serverid,"
            ."servername,opentime) VALUES ($gameid,$groupid,$serverId,"
            ."'$serverName','$opentime')";
        $ret = $this->db->exec($insert);
        if ($ret !== false) {
            $this->GenerateServerCache($gameid);
            return array('status'=>'ok', 'msg'=>'?????');
        }
        else {
            return array('status'=>'fail', 'msg'=>'??????');
        }
    }

    public function Update($id)
    {
        $sql_chk = "SELECT gameid FROM servers_list WHERE id=?";
        $stmt = $this->db->prepare($sql_chk);
        $stmt->execute(array($id));
        $chkExist = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (!$chkExist ) {
            return array('status'=>'fail', 'msg'=>'???????????');
        }
        $gameid = array_shift($chkExist);

        $sql = "UPDATE servers_list SET";
        $vals = array();
        if ( !count($_POST['server']) ) {
            return array('status'=>'fail', 'msg'=>'?????????');
        }
        foreach ($_POST['server'] as $col=>$val) {
            $sql .= "`$col`=?,";
            $vals[] = $val;
        }
        array_push($vals[], $id);
        $sql = rtrim($sql,',') . " WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($vals);
        if ($stmt->rowCount()) {
            $this->GenerateServerCache($gameid);
            return array('status'=>'ok', 'msg'=>'???3??');
        }
    }

    public function Delete($id)
    {
        if (is_array($id)) {
            $id = implode(',', $id);
        }
        $sql = "DELETE FROM servers_list WHERE id IN($id)";
        $rowCount = $this->db->exec($sql);
        if ($rowCount!==false) {
            return array(
                'status'=>'ok',
                'msg'=>"??????{$rowCount}?????");
        }
        else {
            return array(
                'status'=>'fail',
                'msg'=>'??????,???????:'.$this->db->errorInfo());
        }
    }

    public function GenerateServerCache()
    {
        $sql = "SELECT s.gameid, s.groupid, s.serverid, s.servername, "
            ."s.opentime, g.group_name FROM servers_list AS s "
            ."LEFT JOIN servers_group AS g ON s.groupid=g.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $str = "<select multiple='multiple' name='servers' id='servers'>";
        $grps = array();
        foreach ($data as $d) {
            $grps[$d['groupid']] = $d['group_name'];
            $servers[$d['groupid']][$d['serverid']] = $d['servername'];
            $serversList[$d['serverid']] = $d['servername'];
        }
        $data_str = "<?php\n\$servers=" . var_export($servers, true).";\n";
        $data_str .= "\$grps=" . var_export($grps, true).";\n";
        $data_str .= "\$serversList=" . var_export($serversList, true).";\n";

        $fileName = ROOT_PATH."analysis/inc/servers.php";
        file_put_contents($fileName, $data_str);
    }

    /**
     *
     *
     * @return array
     */
    public function FenbaoList()
    {
        $q = $this->db->prepare("select * from user_fenbao");
        $q->execute();
        $ret = $q->fetchAll(PDO::FETCH_ASSOC);
        $out = array();
        foreach($ret as $k=>$v) {
            $out[$v['fenbao_id']] = $v['fenbao_name'];
        }
        $fileName = ROOT_PATH."analysis/inc/fenbao.php";
        $data_str = "<?php\n\$fenbaos=" . var_export($out, true).";\n";
        file_put_contents($fileName, $data_str);
    }

} 