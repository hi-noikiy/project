<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 上午11:47
 */

class System {
    private $_db;
    private $_salt = 'u591';
    public function __construct(PDO $db)
    {
        $this->_db = $db;

    }

    private function _GeneratePassword($pwd)
    {
        if(strlen($pwd)>=6) {
            return md5(md5( $this->_salt.$pwd));
        }
        else {
            return false;
        }
    }

    public function UserShow($uid)
    {
        return $this->UserList($uid);
    }

    public function UserList($uid = 0, $account='', $uname='')
    {
        $sql = "SELECT * FROM s_user WHERE 1=1";
        $search = array();
        $mode = 0;
        if ($uid>0) {
            $sql .= " AND id=:id";
            $search[':id'] = $uid;
            $mode = 1;
        }
        if (!empty($account)) {
            $sql .= " AND account=:account";
            $search[':account'] = $account;
            $mode = 1;
        }
        if (!empty($uname)) {
            $sql .= " AND uname LIKE '%:uname%'";
            $search[':uname'] = $uname;
        }
        $stmt = $this->_db->prepare($sql);
        $stmt->execute($search);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($mode ==1) {
            return array_shift($users);
        }
        return $users;
    }

    public function UserAdd($grpid, $account, $uname, $urights, $pwd, $repwd)
    {
//        extract($_POST['user']);
        if(!$grpid) {
            return array('status'=>'fail','msg'=>$GLOBALS['lang']['umsg_grp']);
        }
        if (!$account) {
            return array('status'=>'fail','msg'=>$GLOBALS['lang']['umsg_ant']);
        }
        if (!$uname) {
            return array('status'=>'fail','msg'=>$GLOBALS['lang']['umsg_ant']);
        }
        if ($repwd!=$pwd) {
            return array('status'=>'fail',
                'msg'=>$GLOBALS['lang']['umsg_pwd_nomatch']);
        }
        $pwd = $this->_GeneratePassword($pwd);
        if (!$pwd ) {
            return array('status'=>'fail',
                'msg'=>$GLOBALS['lang']['umsg_pwd']);
        }

        $sql_chk = "SELECT id FROM s_user WHERE account=? LIMIT 1";
        $stmt = $this->_db->prepare($sql_chk);
        $stmt->execute(array($account));
        if ($stmt->fetchColumn()>0) {
            return array('status'=>'fail',
                'msg'=>$GLOBALS['lang']['umsg_exist']);
        }

        //$r = array_shift($this->GroupList($grpid));
        //$rights = implode(',', $urights);

        $sql = 'INSERT INTO s_user(uname,account,upwd,ugrp,urights,createtime)'
            .' VALUES(?,?,?,?,?, NOW())';
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($uname,$account, $pwd, $grpid, $urights ));
        $stmt->rowCount();
        return array('status'=>'ok', 'msg'=>$GLOBALS['lang']['umsg_add_ok']);
    }

    public function UserUpdate($id, Array $user)
    {
        $sql_chk = "SELECT id FROM s_user WHERE id=?";
        $stmt = $this->_db->prepare($sql_chk);
        $stmt->execute(array($id));
        if (count($stmt->fetchAll(PDO::FETCH_COLUMN))) {
            $up = '';
            if (isset($user['upwd'])) {
                $user['upwd'] = $this->_GeneratePassword($user['upwd']);
            }
            foreach ($user as $col=>$val) {
                $up .= "`$col`='$val',";
            }
            $sql = "UPDATE `s_user` SET ". rtrim($up,',') . " WHERE id=$id";
            if ($this->_db->exec($sql)!==false) {
                return array('status'=>'ok',
                    'msg'=>$GLOBALS['lang']['umsg_update_ok']);
            }
            else {
                return array(
                    'status'=>'fail',
                    'msg'=>$GLOBALS['lang']['umsg_update_fail']
                        .$this->_db->errorInfo());
            }
        }
        else {
            return array(
                'status'=>'fail',
                'msg'=>$GLOBALS['lang']['umsg_no_exist'],
            );
        }
    }

    /**
     * 修改密码
     *
     * @param $id 用户ID
     * @param $old 旧密码
     * @param $new 新密码
     * @param $renew 确认新密码
     * @return array
     */
    public function UserPasswordReset($id, $old, $new, $renew)
    {
        $sql_chk = "SELECT id FROM s_user WHERE id=? AND upwd=?";
        $q = $this->_db->prepare($sql_chk);
        $q->execute(array($id, $this->_GeneratePassword($old)));
        if ( !$q->fetchColumn() ) {
            return array('status'=>'fail',
                'msg'=>$GLOBALS['lang']['umsg_err_oldpwd']);
        }
        if ($new != $renew) {
            return array('status'=>'fail',
                'msg'=>$GLOBALS['lang']['umsg_pwd_nomatch']);
        }
        $sql_up = "UPDATE s_user SET upwd=? WHERE id=?";
        $q = $this->_db->prepare($sql_up);
        $q->execute(array($this->_GeneratePassword($new), $id));
        if ($q->rowCount()) {
            return array('status'=>'ok',
                'msg'=>$GLOBALS['lang']['umsg_resetpwd_ok']);
        }
        return array('status'=>'fail',
                'msg' => $GLOBALS['lang']['umsg_resetpwd_fail']
        );
    }

    /**
     * 用户登录
     *
     * @param $account 账号
     * @param $pwd 密码
     * @return array
     */
    public function UserLogin($account, $pwd)
    {
        $sql = "SELECT id,upwd,ustatus,urights,uname,ugrp FROM s_user WHERE account=?";
        $q = $this->_db->prepare($sql);
        $q->execute(array($account));
        $u = $q->fetchAll(PDO::FETCH_ASSOC);
//        print_r($u);
        if (count($u)) {
            $d = array_shift($u);
            if (!$d['ustatus']) {
                return array('status'=>'fail',
                    'msg'=> $GLOBALS['lang']['umsg_login_forbid']);
            }
//            echo $pwd . '----';
            if ($d['upwd'] != $this->_GeneratePassword($pwd)) {
//                echo $this->_GeneratePassword($pwd);
                return array('status'=>'fail',
                    'msg'=>$GLOBALS['lang']['umsg_login_errpwd']);
            }
            session_start();
            $_SESSION['uid']        = $d['id'];
            $_SESSION['urights']    = $d['urights'];
            $_SESSION['uname']      = $d['uname'];
            $_SESSION['gid']        = $d['ugrp'];
//            print_r($_SESSION);
            $ip                     = $_SERVER['REMOTE_ADDR'];
            // IP转数字函数inet_aton()
            // 数字转IP函数inet_ntoa()
            $sql_up = "UPDATE s_user SET logintime=NOW(),"
                ."loginip='$ip',logincnt=logincnt+1 WHERE account='$account'";
            $this->_db->exec($sql_up);
            return array('status'=>'ok');
        }
        else {
            return array('status'=>'fail',
                'msg'=>$GLOBALS['lang']['umsg_login_errpwd']);
        }
    }

    public function UserGtype()
    {
        if (!$_SESSION['gtype']) {
            $sql_gtype = "SELECT gtype FROM s_user_grp WHERE id={$_SESSION['gid']}";
            $q = $this->_db->prepare($sql_gtype);
            $q->execute();
            $_SESSION['gtype'] = $q->fetchColumn();
        }
        return $_SESSION['gtype'];
    }

    public static function UserLoginCheck()
    {
        return isset($_SESSION['uid']);
    }
    /**
     * 禁用用户账号
     *
     * @param $id
     * @param $status 0禁用1启用
     * @return array
     */
    public function UserDisable($id, $status=0)
    {
        if ($_SESSION['gid']) {
            $gtype = $this->UserGtype();
            if ($gtype>2) {
                return array(
                    'status'=>'fail',
                    'msg'   => $GLOBALS['lang']['umsg_per_dny']
                );
            }
            $sql_forbid = "UPDATE s_user SET ustatus=$status WHERE id=$id";
            $this->_db->exec($sql_forbid);
            return array(
                'status'=>'ok',
                'msg'=>$GLOBALS['lang']['umsg_op_ok']
            );
        }
        else {
            return array(
                'status'=>'fail',
                'msg'=>$GLOBALS['lang']['umsg_unlogin']
            );
        }
    }
    /**
     * 添加用户组
     *
     * @param string $grpname 用户组名称
     * @param int $gtype 用户组分类
     * @param Array $defaultRights 用户组默认权限
     * @return Array
     */
    public function GroupAdd($grpname, $gtype, Array $defaultRights)
    {
        $grights = implode(',', $defaultRights);
        $sql = "INSERT INTO s_user_grp(gname,gtype, grights) VALUES(?,?,?)";
        $q = $this->_db->prepare($sql);
        $q->execute(array($grpname, $gtype ,$grights));
        if ($q->rowCount()>0) {
            return array('status'=>'ok',
                'msg'=>$GLOBALS['lang']['umsg_addgrp_ok']);
        }
        return array('status'=>'fail',
            'msg'=>$GLOBALS['lang']['umsg_addgrp_fail']) . $q->errorInfo();
    }

    public function GroupUpdate($gid, $gname, $gtype ,Array $grights)
    {
        $grights = implode(',', $grights);
        $sql = "UPDATE s_user_grp SET gname=?, `gtype`=?,`grights`=? WHERE id=?";
        $q = $this->_db->prepare($sql);
        $q->execute(array($gname, $gtype, $grights, $gid));
        if ($q->rowCount()) {
            return array(
                'status'=>'ok',
                'msg'   =>$GLOBALS['lang']['umsg_upgrp_ok']
            );
        }
        return array(
            'status'=>'fail',
            'msg'   =>$GLOBALS['lang']['umsg_upgrp_fail'] . $q->errorInfo()
        );
    }

    /**
     * 获取用户组列表
     *
     * @param int $gid
     * @return array
     */
    public function GroupList($gid=0)
    {
        $where = ' WHERE gtype>1';
        if ($gid) {
            $where .= ' AND id=' . $gid;
        }
        $sql = "SELECT * FROM s_user_grp" . $where.' ORDER BY gtype ASC';
       // echo $sql;
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function UserRightsChk(PDO $db, $filename)
    {
        $sql = "SELECT `id`,`gid` FROM `s_files` WHERE `fpath`='$filename'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if(strtoupper($_SESSION['urights'])=='ALL') {
            return $data;
        }
        if ( !in_array($data['id'], explode(',', $_SESSION['urights']), true )) {
            return false;
        }
        return $data;
    }

    public function GenerateToken()
    {
        if (!$_SESSION['token']) {
            $_SESSION['token'] = md5($_SESSION['account'] . date('Ymd'));
        }
        return $_SESSION['token'];
    }
    public function GroupFileList($grpid)
    {
        $sql = "SELECT grights FROM s_user_grp WHERE id=$grpid";
//        echo $sql;
        $q = $this->_db->prepare($sql);
        $q->execute();
        $r = rtrim($q->fetchColumn(), ',');

        $fl = $this->FileList($r);
        $files = array();
        foreach ($fl as $f) {
            $files[$f['gid']][] = array(
                'id' => $f['id'],
                'title_zh_CN' => $f['ftitle_zh'],
                'title_en_US' => $f['ftitle_en']
            );
        }
        return $files;
    }

    public function FilesFormat($arr, $urights='')
    {
        $s = '';
        $ura = array();
        if ($urights!='all' && !empty($urights)) {
            $ura = explode(',', $urights);
        }
        foreach ($arr as $gid=>$files) {
            $navTitle = $GLOBALS['lang'][$GLOBALS['navGrp'][$gid]];
            $s .= "<label>{$navTitle}</label><p>";
            foreach ($files as $file) {
                $title = $file['title_'.$_COOKIE['lang']];
                $s .= "<label class='checkbox-inline'>"
                    ."<input name='user[urights][]' type='checkbox'";
                if (count($ura)==0 || in_array($file['id'], $ura)) {
                    $s .= " checked='checked'";
                }
                $s .= " value='{$file['id']}'>{$title}</label>";
            }
            $s .= "</p>";
        }
        return $s;
    }

    /**
     * 添加程序文件，只有超级管理员才能添加
     *
     * @param $path
     * @param $title_zh
     * @param $title_en
     * @param $gid
     * @return array
     */
    public function FileAdd($path, $title_zh, $title_en, $gid)
    {

        if ( $this->UserGtype() !=1 ) {
            return array(
                'status'=>'fail',
                'msg'=>'权限不足'
            );
        }
        $sql = "INSERT INTO s_files(fpath,ftitle_zh,ftitle_en,gid) VALUES(?,?,?,?)";
        $q = $this->_db->prepare($sql);
        $q->execute(array($path, $title_zh, $title_en,$gid));
        if ($q->rowCount()) {
            $this->GenerateFilesCache();
            return array(
                'status'=>'ok',
                'msg'=>'添加成功'
            );
        }
        else {
            return array(
                'status' => 'fail',
                'msg'    => '添加失败！' . $q->errorInfo()
            );
        }
    }
    public function FileUpdate($id, $path, $title_zh, $title_en, $gid)
    {

        if ( $this->UserGtype() !=1 ) {
            return array(
                'status'=>'fail',
                'msg'=>'权限不足'
            );
        }
        $sql = "UPDATE s_files SET fpath=?,ftitle_zh=?,ftitle_en=?,gid=? WHERE id=?";
        $q = $this->_db->prepare($sql);
//        echo $sql;
        $q->execute(array($path, $title_zh, $title_en,$gid, $id));
//        print_r(array($path, $title_zh, $title_en,$gid, $id));
        if ($q->rowCount()!==false) {
            $this->GenerateFilesCache();
            return array(
                'status'=>'ok',
                'msg'=>'修改成功'
            );
        }
        else {
            return array(
                'status' => 'fail',
                'msg'    => '修改失败！' . implode(',',$q->errorInfo())
            );
        }
    }


    /**
     * 文件排序
     *
     * @param $id
     * @param $fsort
     * @return int
     */
    public function FileSort($id, $fsort)
    {
        $sql = "UPDATE s_files SET fsort=$fsort WHERE id=$id";
//        echo $sql;
        $r = $this->_db->exec($sql);
        $this->GenerateFilesCache();
        return $r;
    }

    /**
     * 渠道排序
     *
     * @param $id
     * @param $fsort
     * @return int
     */
    public function FenbaoSort($id, $fsort)
    {
        $sql = "UPDATE user_fenbao SET fenbao_sort=$fsort WHERE id=$id";
        $r = $this->_db->exec($sql);
        $this->GenerateFenBaoCache();
        return $r;
    }

    public function FileList($fileid=null, $status=-1)
    {
        $sql = "SELECT * FROM s_files WHERE 1=1";
        if ($status>-1) {
            $sql .= " AND fstatus=$status";
        }
        if (!is_null($fileid)) {
            $sql .= " AND id IN($fileid)";
        }

        $sql .= " order by gid asc,fsort ASC";
        $q = $this->_db->prepare($sql);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_ASSOC);

    }

    public function ServerGroupList()
    {
        $sql = <<<SQL
SELECT g.id, g.group_name, count(s.id) AS cnt FROM servers_group g
LEFT JOIN servers_list s ON s.groupid = g.id group by g.id ORDER BY g.id ASC
SQL;
        $q = $this->_db->prepare($sql);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    public function SeverList($id=0)
    {

        $sql = "SELECT * FROM servers_list";
        if ($id>0) {
            $sql .= " WHERE id=$id";
        }
        $sql .= " order by groupid asc,serverid asc";
        $q = $this->_db->prepare($sql);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_ASSOC);

    }


    public function AddServerGroup()
    {
        $name = trim($_POST['group_name']);
        $sql = "INSERT INTO servers_group(group_name) VALUES ('$name')";
        $ret = $this->_db->exec($sql);
        if ($ret !== false) {
            $this->GenerateServerGroupCache();
            return array('status'=>'ok', 'msg'=>'添加成功');
        }
        else {
            return array('status'=>'fail', 'msg'=>'添加失败');
        }
    }

    /**
     * 添加区服
     * @return array
     */
    public function AddServer()
    {

        $serverId   = intval($_POST['serverid']);
        if ($serverId > 10000) {
            return array('status'=>'fail', 'msg'=>'区服ID超出10000了');
        }
        $serverName = trim($_POST['servername']);
        $groupid    = intval($_POST['groupid']);
        $id = isset($_POST['sid']) ? intval($_POST['sid']) : 0;
        if($id>0) {
            $sql = "UPDATE servers_list SET groupid=$groupid,"
                 . "serverid=$serverId,servername='$serverName' WHERE id=$id";
        }
        else {
            $sql = "INSERT INTO servers_list(groupid,serverid,"
                ."servername,opentime) VALUES ($groupid,$serverId,"
                ."'$serverName',now())";
        }
//        echo $insert;
        $ret = $this->_db->exec($sql);
        if ($ret !== false) {
            $this->GenerateServerCache();
            return array('status'=>'ok', 'msg'=>'添加成功');
        }
        else {
            return array('status'=>'fail', 'msg'=>'添加失败');
        }
    }

    public function AddChannel()
    {
        $fenbao_sort = $_POST['fenbao_sort'] ? intval($_POST['fenbao_sort']) : 0;
        $fenbao_name = trim($_POST['fenbao_name']);
        $fenbao_id   = trim($_POST['fenbao_id']);

        $sqlInsert = "INSERT INTO user_fenbao (fenbao_name,fenbao_id,fenbao_sort) VALUES (?,?,?)";
        $q = $this->_db->prepare($sqlInsert);
        $ret = $q->execute(array($fenbao_name, $fenbao_id, $fenbao_sort));
    }

    /**
     * 生成文件缓存
     *
     */
    public function GenerateFilesCache()
    {
        $files = $this->FileList(null, 1);
        $c = array();
        $c2 = array();
        foreach ($files as $f) {
            $c[$f['gid']][] = array(
                'id'            => $f['id'],
                'title_zh_CN'   => $f['ftitle_zh'],
                'title_en_US'   => $f['ftitle_en'],
                'path'          => $f['fpath'],
            );
            $c2[$f['id']] = array(
                'title_zh_CN'   => $f['ftitle_zh'],
                'title_en_US'   => $f['ftitle_en'],
                'path'          => $f['fpath'],
            );
        }
        $s = "<?php\n\$files = " . var_export($c, true) . ";\n";
        $s .= "\$files_no_grp = " . var_export($c2, true) . ";\n?>";
        file_put_contents(A_ROOT.'inc/files.inc.php', $s);
    }

    public function GenerateServerGroupCache()
    {
        $sql = "SELECT id,group_name FROM servers_group";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $grps = array();
        foreach ($data as $d) {
            $grps[$d['id']] = $d['group_name'];
        }
        $data_str = "<?php\n\r return " . var_export($grps, true).";\n";
        $fileName = ROOT_PATH."analysis/inc/server_group.inc.php";
        file_put_contents($fileName, $data_str);
    }

    public function GenerateServerCache()
    {
        $sql = "SELECT s.groupid, s.serverid, s.servername, "
            ."s.opentime, g.group_name FROM servers_list AS s "
            ."LEFT JOIN servers_group AS g ON s.groupid=g.id";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grps = array();
        foreach ($data as $d) {
            $grps[$d['groupid']]                    = $d['group_name'];
            $servers[$d['groupid']][$d['serverid']] = $d['servername'];
            $serversList[$d['serverid']]            = $d['servername'];
        }
        $data_str1 = "<?php\n\r return " . var_export($servers, true).";\n";
        $fileName = ROOT_PATH."analysis/inc/server_list.inc.php";
        file_put_contents($fileName, $data_str1);

        $data_str = "<?php\n\$servers=" . var_export($servers, true).";\n";
        $data_str .= "\$grps=" . var_export($grps, true).";\n";
        $data_str .= "\$serversList=" . var_export($serversList, true).";\n";

        $fileName = ROOT_PATH."analysis/inc/servers.php";
        file_put_contents($fileName, $data_str);
    }

    public function GenerateFenBaoCache()
    {
        $sql = "SELECT * FROM user_fenbao ORDER BY fenbao_sort ASC,fenbao_id ASC";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $fenBaos = array();
        foreach ($data as $d) {
            $fenBaos[$d['fenbao_id']] = $d['fenbao_name'];
        }
        $data_str = "<?php\n\$fenbaos=" . var_export($fenBaos, true).";\n";
        $fileName = ROOT_PATH."analysis/inc/fenbao.php";
        file_put_contents($fileName, $data_str);
    }

    public function __call($name, $arguments)
    {
        echo "Method [$name] does not exist!.";
    }

} 