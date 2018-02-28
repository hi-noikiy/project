<?php
/**
 * Created by PhpStorm.
 * User: Guangpeng Chen
 * Date: 15-11-7
 * Time: 下午9:49
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Mongo {
    private $CI;
    private $config = array();
    private $param = array();
    private $activate;
    private $connect;
    private $db;
    private $hostname;
    private $port;
    private $database;
    private $username;
    private $password;
    private $debug;
    private $write_concerns;
    private $journal;
    private $selects = array();
    private $updates = array();
    private $wheres	= array();
    private $limit	= 999999;
    private $offset	= 0;
    private $sorts	= array();
    private $return_as = 'array';

    public function __construct($param)
    {
        if ( ! class_exists('Mongo') && ! class_exists('MongoClient'))
        {
            show_error("The MongoDB PECL extension has not been installed or enabled", 500);
        }
        $this->CI =& get_instance();
        $this->CI->load->config('mongo_db');
        $this->config = $this->CI->config->item('mongo_db');
        $this->connect();
    }

    function __destruct()
    {
        if(is_object($this->connect))
        {
            $this->connect->close();
        }
    }
    private function connect()
    {
        $this->prepare();
        try
        {
            $dns = "mongodb://{$this->hostname}:{$this->port}/{$this->database}";
            if(isset($this->config[$this->activate]['no_auth']) == TRUE && $this->config[$this->activate]['no_auth'] == TRUE)
            {
                $options = array();
            }
            else
            {
                $options = array('username'=>$this->username, 'password'=>$this->password);
            }
            $this->connect = new MongoClient($dns, $options);
            $this->db = $this->connect->selectDB($this->database);
            $this->db = $this->connect->{$this->database};
        }
        catch (MongoConnectionException $e)
        {
            if(isset($this->debug) == TRUE && $this->debug == TRUE)
            {
                show_error("Unable to connect to MongoDB: {$e->getMessage()}", 500);
            }
            else
            {
                show_error("Unable to connect to MongoDB", 500);
            }
        }
    }
    private function prepare()
    {
        if(is_array($this->param) && count($this->param) > 0 && isset($this->param['activate']) == TRUE)
        {
            $this->activate = $this->param['activate'];
        }
        else if(isset($this->config['active']) && !empty($this->config['active']))
        {
            $this->activate = $this->config['active'];
        }else
        {
            show_error("MongoDB configuration is missing.", 500);
        }

        if(isset($this->config[$this->activate]) == TRUE)
        {
            if(empty($this->config[$this->activate]['hostname']))
            {
                show_error("Hostname missing from mongodb config group : {$this->activate}", 500);
            }
            else
            {
                $this->hostname = trim($this->config[$this->activate]['hostname']);
            }

            if(empty($this->config[$this->activate]['port']))
            {
                show_error("Port number missing from mongodb config group : {$this->activate}", 500);
            }
            else
            {
                $this->port = trim($this->config[$this->activate]['port']);
            }

            if(empty($this->config[$this->activate]['username']))
            {
                show_error("Username missing from mongodb config group : {$this->activate}", 500);
            }
            else
            {
                $this->username = trim($this->config[$this->activate]['username']);
            }

            if(empty($this->config[$this->activate]['password']))
            {
                show_error("Password missing from mongodb config group : {$this->activate}", 500);
            }
            else
            {
                $this->password = trim($this->config[$this->activate]['password']);
            }

            if(empty($this->config[$this->activate]['database']))
            {
                show_error("Database name missing from mongodb config group : {$this->activate}", 500);
            }
            else
            {
                $this->database = trim($this->config[$this->activate]['database']);
            }

            if(empty($this->config[$this->activate]['db_debug']))
            {
                $this->debug = FALSE;
            }
            else
            {
                $this->debug = $this->config[$this->activate]['db_debug'];
            }

            if(empty($this->config[$this->activate]['write_concerns']))
            {
                $this->write_concerns = 1;
            }
            else
            {
                $this->write_concerns = $this->config[$this->activate]['write_concerns'];
            }

            if(empty($this->config[$this->activate]['journal']))
            {
                $this->journal = TRUE;
            }
            else
            {
                $this->journal = $this->config[$this->activate]['journal'];
            }

            if(empty($this->config[$this->activate]['return_as']))
            {
                $this->return_as = 'array';
            }
            else
            {
                $this->return_as = $this->config[$this->activate]['return_as'];
            }
        }
        else
        {
            show_error("mongodb config group :  <strong>{$this->activate}</strong> does not exist.", 500);
        }
    }

    public function insert($collection = "", $insert = array())
    {
        if (empty($collection))
        {
            show_error("No Mongo collection selected to insert into", 500);
        }

        if (!is_array($insert) || count($insert) == 0)
        {
            show_error("Nothing to insert into Mongo collection or insert is not an array", 500);
        }

        try
        {
            $this->db->{$collection}->insert($insert, array('w' => $this->write_concerns, 'j'=>$this->journal));
            if (isset($insert['_id']))
            {
                return ($insert['_id']);
            }
            else
            {
                return (FALSE);
            }
        }
        catch (MongoCursorException $e)
        {
            if(isset($this->debug) == TRUE && $this->debug == TRUE)
            {
                show_error("Insert of data into MongoDB failed: {$e->getMessage()}", 500);
            }
            else
            {
                show_error("Insert of data into MongoDB failed", 500);
            }
        }
    }

    /**
     * --------------------------------------------------------------------------------
     * Batch Insert
     * --------------------------------------------------------------------------------
     *
     * Insert a multiple document into the collection
     *
     * @usage : $this->mongo_db->batch_insert('foo', $data = array());
     */
    public function batch_insert($collection = "", $insert = array())
    {
        if (empty($collection))
        {
            show_error("No Mongo collection selected to insert into", 500);
        }
        if (count($insert) == 0 || !is_array($insert))
        {
            show_error("Nothing to insert into Mongo collection or insert is not an array", 500);
        }
        try
        {
            $this->db->{$collection}->batchInsert($insert, array('w' => $this->write_concerns, 'j'=>$this->journal));
            if (isset($insert['_id']))
            {
                return ($insert['_id']);
            }
            else
            {
                return (FALSE);
            }
        }
        catch (MongoCursorException $e)
        {
            if(isset($this->debug) == TRUE && $this->debug == TRUE)
            {
                show_error("Batch insert of data into MongoDB failed: {$e->getMessage()}", 500);
            }
            else
            {
                show_error("Batch insert of data into MongoDB failed", 500);
            }
        }
    }
}