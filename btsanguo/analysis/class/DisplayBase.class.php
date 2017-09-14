<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-10
 * Time: 下午5:20
 */

class DisplayBase {
    protected $_db;
    protected $gameid;
    protected $bt;
    protected $et;
    protected $where;
    public function __construct(PDO $db, $gameid=5, $bt=null, $et=null,
                                Array $serverid=array(),Array $fenbaoid=array())
    {
//        $this->_db->exec("SET NAMES 'utf8';");
        $this->_db = $db;
        $this->gameid = $gameid;
        $this->bt = is_null($bt) ? date('Ymd', strtotime('-7 days')) :date('Ymd', strtotime($bt));
        $this->et = is_null($et) ? date('Ymd', strtotime('-1 days')) :date('Ymd', strtotime($et));
        $where = " gameid={$this->gameid}";
        if (!is_null($et)) {
            $where .= " AND sday>={$bt} AND sday <=$et";
        }
        $lenServer = count($serverid);
        if ($lenServer==1) {
            $where .= " AND serverid=" . array_shift($serverid);
        }
        elseif ($lenServer>1) {
            $where .= " AND serverid IN(" . implode(',', $serverid).')';
        }
        $lenFenbao = count($fenbaoid);
        if ($lenFenbao==1) {
            $where .= " AND fenbaoid=" . array_shift($fenbaoid);
        }
        elseif ($lenFenbao>1) {
            $where .= " AND fenbaoid IN(" . implode(',', $fenbaoid).')';
        }
        $this->where = $where;
    }
    public function __destruct()
    {
        $this->_db = null;
    }
} 