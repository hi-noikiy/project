<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('BASE_LOG_DIR')) define('BASE_LOG_DIR', '/data/log/site/');
/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

	public static function log($word, $file_path=''){
		$date = date('Ymd');
        if (PHP_SAPI=='cli') {
            $date = "cli_$date";
        }
		$filepath= empty($file_path) ? APPPATH . "/logs/controller/debug_$date.log" : $file_path;
		$word = date("Y-m-d H:i:s").'#'. $word;
		$fp = fopen($filepath,"a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"\n".$word);
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	public static function BetterLog($path, $content, $pathMode='day')
    {
        $path    = strval($path);
        $path    = str_replace("\\", '/', trim($path, '/'));
        $content = strval($content);
        if(!$path || !$content) {
            return false;
        }

        $pathMode = in_array($pathMode, array('day', 'month', 'year')) ? $pathMode : 'day';
        if (PHP_SAPI =='cli')  $path = 'cli_' . $path;

        $tmpPath = BASE_LOG_DIR . '/' . $path . '_';
        $fileName = date('Y') . '.log';
        if($pathMode == 'day') {
            $fileName = date('Ymd')  . '.log';
        } elseif($pathMode == 'month') {
            $fileName = date('Ym') . '.log';
        }

        //如果文件不存在，就创建文件
        //if(!file_exists($tmpPath)) {
        //    $res = mkdir($tmpPath, 0777, true);
        //    if(!$res) {
        //        return false;
        //    }
        //}

        //内容写入日志文件
        $file    = $tmpPath . $fileName;
        $content = date('Y-m-d H:i:s') . ' # ' . $content . "\r\n";
        $res     = @file_put_contents($file, $content, FILE_APPEND);
        if($res) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 批量添加数据
     * @param unknown $table
     * @param unknown $savedatas
     * @param string $db
     * @return boolean
     * 
     * @author 王涛 20170407
     */
    public function insert_batch($table,$savedatas,$db='')
    {
    	if($savedatas){
    		$this->data_multi = $savedatas;
    	}
    	$sql = "insert into $table(".implode(',', array_keys($this->data_multi[0])).") values";
    	foreach ($this->data_multi as $key=>$value){
    		//$sql .= "(".implode(',', array_values($value))."),";
    		$sql .= "(".$this->implode_new(',', array_values($value))."),";
    	}
    	//$result = $this->db->query();
    	$msql = rtrim($sql,',') . " ON DUPLICATE KEY UPDATE ";
    	foreach ($this->data_multi[0] as $k=>$v){
    		$msql .= "$k=values($k),";
    	}
    	if(!$db){
    		$db = $this->load->database('sdk',true);
    	}
    	$result = $db->query(rtrim($msql,','));
    	if($result){
    		return true;
    	}
    	return false;
    }
    private function implode_new($sp , $data){
    	$str = '';
    	foreach ($data as $v){
    		$str .= "'{$v}'"."$sp";
    	}
    	return rtrim($str,"$sp");
    }
}
