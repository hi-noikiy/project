<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-1
 * Time: 下午5:25
 * 写日志
 */

class Log {
    const MAX_FILE_SIZE = 10485760;//1024*1024*10;
    private $filepath;
    private $fp;
    public function __construct($filepath)
    {
        $this->filepath = $filepath;
        //$this->ChkFileSize();
        $this->fp = fopen($filepath,"a");
        flock($this->fp, LOCK_EX) ;
    }

    private function ChkFileSize()
    {
        if(file_exists($this->filepath)){
            $filesize = filesize($this->filepath);
            $filetype = end(explode(".", $this->filepath)); //获取文件后缀名
            $filename = substr($this->filepath, 0, strpos($this->filepath,'.'));
            if($filesize>self::MAX_FILE_SIZE) {
                rename($this->filepath, $filename.'_'.date('Ymd').'.'.$filetype);
            }
        }
    }
    public function write($word)
    {
        fwrite($this->fp,"\n" .date('Y-m-d H:i:s').'|'.$word);
    }

    public function closeFile()
    {
        flock($this->fp, LOCK_UN);
        fclose($this->fp);
    }
} 