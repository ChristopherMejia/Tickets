<?php

class  ERROR_LOG
{
    private $db;
    private $_table = 'log';
    protected $_path;
    protected $_fileName = 'log_errors.log';

    /**
     * @param string $path can be a directory o a file path
     */
    function __construct($path, $con)
    {
        if (empty($path)) {
            Throw new Exception("Path must be filled");
        }
        if (!file_exists($path)) {
            Throw new Exception("The Path doesn't exists.");
        }
        if (!is_writeable($path)) {
            Throw new Exception("You can write on the give path");
        }
        $this->_path = $this->_parsePath($path);
        $this->db = $con;
    }

    /**
     * Validate the path the add the filename to the path
     * @param String $path
     * @return String
     */
    protected function _parsePath($path)
    {
        $strLenght = strlen($path);
        $lastChar = substr($path, $strLenght - 1, $strLenght);
        $path = $lastChar != "/" ? $path . "/" : $path;

        if (is_dir($path)) {
            return $path . $this->_fileName;
        } else {
            return $path;
        }
    }

    /**
     * Will save the path on the give path
     * @param String $line
     */
    protected function _save($line)
    {
        $fhandle = fopen($this->_path, "a+");
        fwrite($fhandle, $line);
        fclose($fhandle);
    }

    /**
     * main function to add lines to the logging file
     * @param String $line
     */
    public function addLine($user, $program, $prefix, $action, $values, $message, $priority)
    {
        $values = is_array($values) ? print_r($values, true) : $values;
        $values = date("d-m-Y h:i:s") . " - " . $program . " - " . $prefix . " -  $message - " . $values . " \n";
        $this->sqlLogErrors($program, $prefix, $action, $user, $values, $message, $priority);
        $this->_save($values);

    }

    public function sqlLogErrors($program, $prefix, $action, $user, $parameters, $message, $priority)
    {
        $sql = "INSERT INTO log (program, `prefix`, `action`,`user`, priority, parameters, message, logtime, type) VALUES('$program', '$prefix', '$action', '$user', $priority, '$parameters', '$message', NOW(),'error')";
        $data = mysqli_query($this->db, $sql);
    }

}

class  ACTION_LOG
{
    private $db;
    private $_table = 'log';
    protected $_path;
    protected $_fileName = 'log_actions.log';

    /**
     * @param string $path can be a directory o a file path
     */
    function __construct($path, $con)
    {
        if (empty($path)) {
            Throw new Exception("Path must be filled");
        }
        if (!file_exists($path)) {
            Throw new Exception("The Path doesn't exists.");
        }
        if (!is_writeable($path)) {
            Throw new Exception("You can write on the give path");
        }
        $this->_path = $this->_parsePath($path);
        $this->db = $con;
    }

    /**
     * Validate the path the add the filename to the path
     * @param String $path
     * @return String
     */
    protected function _parsePath($path)
    {
        $strLenght = strlen($path);
        $lastChar = substr($path, $strLenght - 1, $strLenght);
        $path = $lastChar != "/" ? $path . "/" : $path;

        if (is_dir($path)) {
            return $path . $this->_fileName;
        } else {
            return $path;
        }
    }

    /**
     * Will save the path on the give path
     * @param String $line
     */
    protected function _save($line)
    {
        $fhandle = fopen($this->_path, "a+");
        fwrite($fhandle, $line);
        fclose($fhandle);
    }

    /**
     * main function to add lines to the logging file
     * @param String $line
     */
    public function addLine($user, $program, $prefix, $action, $values, $message, $priority)
    {

        $values = is_array($values) ? print_r($values, true) : $values;
        $values = date("d-m-Y h:i:s") . " - " . $program . " - " . $prefix . " -  $message - " . $values . " \n";
        $this->sqlLogActions($program, $prefix, $action, $user, $values, $message, $priority);
        $this->_save($values);

    }

    public function sqlLogActions($program, $prefix, $action, $user, $parameters, $message, $priority)
    {
        $sql = "INSERT INTO log (program, `prefix`, `action`, `user`, priority, parameters, message, logtime, `type`) VALUES('$program', '$prefix','$action', '$user', $priority, '$parameters', '$message', NOW(),'action')";
        $data = mysqli_query($this->db, $sql);
    }

}