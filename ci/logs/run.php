<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 12/4-004
 * Time: 20:09
 */
//遍历目录
class RecursiveFileFilterIterator extends FilterIterator {
    // 满足条件的扩展名
    protected $ext = array('log');
    /**
     * 提供 $path 并生成对应的目录迭代器
     */
    public function __construct($path) {
        parent::__construct(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)));
    }

    /**
     * 检查文件扩展名是否满足条件
     */
    public function accept() {
        $item = $this->getInnerIterator();
        if ($item->isFile() &&
            in_array(pathinfo($item->getFilename(), PATHINFO_EXTENSION), $this->ext)) {
            return TRUE;
        }
    }
}
// 实例化
//$lod_path = __DIR__;
//$tmp = "INSERT INTO u_register_tmp(accountid,mac,channel,client_type,ip,created_at,appid,regway,reg_date) VALUES";
$tmp = "INSERT INTO u_register(accountid,mac,channel,client_type,ip,created_at,appid,regway,reg_date) VALUES";
foreach (new RecursiveFileFilterIterator(__DIR__) as $file) {
    $values = '';
    $handle = fopen($file, "r");
    if ($handle) {
        while (!feof($handle)) {
            $buffer = fgets($handle, 4096);
            try {
                if (!$buffer) break;
                //echo $buffer,"\n";
                $pos = strpos($buffer, '-',  8);
                if ($pos===false) continue;
                $time = substr($buffer,0, $pos);
                //echo $time,"\n";
                $str = substr($buffer, $pos+1);
                //echo $str,"\n";
                parse_str($str, $data);
                $created_at = strtotime($time);
                $ip = isset($data['ip']) ? ip2long($data['ip']) : 0;
                $data['clienttype'] = rtrim($data['clienttype']);
                $reg_date = date('Ymd', $created_at);
                $ip = !$ip ? 0 : $ip;
                $values .= "({$data['accountid']}, '{$data['mac']}', {$data['fenbaoid']}, '{$data['clienttype']}', $ip,$created_at, 10002,0,$reg_date),\n";
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        fclose($handle);
        $sql = $tmp . rtrim($values, ",\n") . ";\n";
        $name = rtrim(basename($file), '.log');
        //file_put_contents("sql/$name.sql", $sql);
        file_put_contents("sql/reg.sql", $sql, FILE_APPEND);
    }
    //break;
}