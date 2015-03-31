<?php

/**
 * Base driver classes
 */

class EDBDriver{
    protected $server = null;
    protected $username = null;
    protected $password = null;
    protected $database = null;
    
    /*
    * Establece la configuracion para la conexion
    * @version: 1.0
    * @author: ironhandlord@gmail.com
    * @date: 2011-06-23 23:31
    */
    protected function setConfig($config){
        $this->server = $config['database']['server'];
        $this->username = $config['database']['username'];
        $this->password = $config['database']['password'];
        $this->database = $config['database']['database'];
    }
    
}
?>
