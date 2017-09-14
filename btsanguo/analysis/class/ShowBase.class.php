<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-10
 * Time: 下午5:20
 */

class ShowBase {
    protected $_db;
    protected $gameid;
    protected $where;
    protected $group = 'GROUP BY sday';
    public function __construct(PDO $db, $gameid=5, $bt=null, $et=null,
                                Array $serverid=array(),Array $fenbaoid=array())
    {
//        $this->_db->exec("SET NAMES 'utf8';");
        $this->_db = $db;
        $bt = is_null($bt) ? date('Ymd', strtotime('-7 days')) :date('Ymd', strtotime($bt));
        $et = is_null($et) ? date('Ymd', strtotime('-1 days')) :date('Ymd', strtotime($et));
        $this->where = "WHERE gameid={$gameid}";
        if (!is_null($et)) {
            $this->where .= " AND sday>={$bt} AND sday <=$et";
        }
        else {
            $this->where .= "  AND sday={$bt}";
        }
        $lenServer = count($serverid);
        if ($lenServer==1) {
            $this->where .= " AND serverid=" . array_shift($serverid);
        }
        elseif ($lenServer>1) {
            $this->where .= " AND serverid IN(" . implode(',', $serverid).')';
            $this->group .= ',serverid';
        }
        $lenFenbao = count($fenbaoid);
        if ($lenFenbao==1) {
            $this->where .= " AND fenbaoid=" . array_shift($fenbaoid);
        }
        elseif ($lenFenbao>1) {
            $this->where .= " AND fenbaoid IN(" . implode(',', $fenbaoid).')';
            $this->group .= ',fenbaoid';
        }
    }
    public function __destruct()
    {
        $this->_db = null;
    }
} 