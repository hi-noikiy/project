<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 14-7-6
 * Time: 下午10:21
 */

abstract class Base {
    protected $_souce_db;
    protected $_sum_db;

    public $timestamp;
    public $bt;
    public $et;
    public $sday;
    public $gameid;

    public function __construct(PDO $souceDb,PDO $sumDb, $bt=null, $gameId=5 )
    {
        $this->_souce_db = $souceDb;
        $this->_sum_db   = $sumDb;
        if (is_null($bt)) {
            $this->timestamp = strtotime('-1 days');
        }
        else {
            $this->timestamp = strtotime($bt);
        }
        $this->sday     = date('Ymd', $this->timestamp);
        $this->bt       = date('ymd0000', $this->timestamp);
        $this->et       = date('ymd2359', $this->timestamp);
        $this->gameid   = $gameId;


    }
    abstract  function run();
    public function Insert($data, $table)
    {
        $insert_values = array();
        $row = 0;
        foreach($data as $d){
            $keys = implode(',', array_keys($d));
            $values = array_values($d);
            $question_marks = '('  . placeholders('?', sizeof($d)) . ')';

            $sql = "INSERT INTO `{$table}` (" . implode(",", array_keys($d) )
                . ") VALUES " .  $question_marks;
            $stmt = $this->_sum_db->prepare ($sql);
            $stmt->execute($values);
            $row += $stmt->rowCount();
            //$insert_values = array_merge($insert_values, array_values($d));
        }
        return $row;
//        $sql = "INSERT INTO `{$table}` (" . implode(",", array_keys($data[0]) )
//            . ") VALUES " . implode(',', $question_marks);
//        echo $sql;
//        exit;
//        $stmt = $this->_sum_db->prepare ($sql);
//        try {
//            $stmt->execute($insert_values);
//            return $stmt->rowCount();
//        } catch (PDOException $e){
//            return $e->getMessage();
//        }

    }
} 