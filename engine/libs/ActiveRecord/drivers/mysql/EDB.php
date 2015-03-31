<?php

/*
 * Mysql DB class driver
 */

class EDB extends EDBDriver{
    
    private $link = null;
    public $model = null;

    public $stringTypes = array(
         'char',
         'varchar',
         'binary',
         'varbinary',
         'tinyblob',
         'blob',
         'mediumblob',
         'longblob',
         'tinytext',
         'text',
         'mediumtext',
         'longtext',
         'enum',
         'date',
         'datetime',
         'time'
    );

    /**
     * Constructor de la clase
     */
    public function __construct($config=array()) {
        $this->setConfig(isset($config['database']['database'])?$config:Engine::getConfig());
        $this->connect();
        $this->selectdb();
    }


    /**
    * Conexion a la base de datos
    * @version: 1.0
    * @author: ironhandlord@gmail.com
    * @since: 2010-09-13 21:25
    */
    private function  connect() {
        if(!$this->link = @mysql_connect($this->server, $this->username,  $this->password)){
            $this->MyError('No se pudo realizar la conexi&oacute;n con la base de datos.');
            exit();
        }
    }

    /**
    * Selecciona la base de datos
    * @version: 1.0
    * @author: ironhandlord@gmail.com
    * @since: 2010-09-13 21:25
    */
   public function  selectdb($database = null) {
        if(!is_null($database)) $this->database = $database;
        if(!mysql_select_db( $this->database, $this->link)){
            $this->MyError('No se pudo seleccionar la base de datos.');
            exit();
        }
        //mysql_set_charset('utf8', $this->link);//sec: establece el tipo de caracteres que usara en las query. Advertencia: todas las tablas deben ser UTF-8
    }

    /**
    * Devuelve un array con la especificacion del modelo
    * @version: 1.0
    * @author: ironhandlord@gmail.com
    * @since: 2010-09-14 20:57
    * @return array
    */
    public function  describeModel() {
        $rs = $this->query('DESCRIBE '.$this->model);
        $aModel = array();
        
//        $rs = $this->query('SELECT * FROM '.$this->model. ' LIMIT 1');
//        $aModel = array();
//        while($meta = mysql_fetch_field($rs)){
//            $aModel[] = $meta;
//        }
        
        while($rw = mysql_fetch_array($rs, MYSQL_ASSOC)){
            $aModel[$rw['Field']] = $rw;
        }
        return $aModel;
    }

    /**
     * Devuelve un array con los campos clave primaria del modelo
     * @author ironhandlord@gmail.com
     * @param type param
     * @return array
     */
    public function getPrimaryKey($returnType=false) {
        $aFields = $this->describeModel();
        $aPK = array();
        foreach ($aFields as $field){
            if($field[3] == 'PRI'){
                if($returnType) $aPK[] = array($field[0], $field[1]);
                else $aPK[] = $field[0];
            }
        }
        return $aPK;
    }


    /**
    * Hace consultas desde un string SQL
    * @version: 1.0
    * @author: ironhandlord@gmail.com
    * @since: 2010-09-13 21:27
    * @param string sql
    * @return ResultSet
    */
    public function query($sql, $logsql = false) {
        if($logsql) echo $sql;
        $this->selectdb();
        $rs = mysql_query($sql, $this->link) or die($this->MyError('Error In Query '.$sql));
        return $rs;
    }

    /**
    * Ejecuta consultas desde un string SQL
    * @version: 1.0
    * @author: ironhandlord@gmail.com
    * @since: 2010-09-13 21:27
    * @param string sql
    */
    public function execQuery($sql, $logsql = false) {
        if($logsql) echo $sql;
        $this->selectdb();
        $rs = mysql_query($sql, $this->link) or die($this->MyError('Error In Query '.$sql));
    }
    /**
     *
     */

    /**
     * Devuelve el registro de un ResultSet en un array asociativo
     * @version: 1.0
     * @author: ironhandlord@gmail.com
     * @since: 2010-09-12 22:30
     * @param ResultSet rs
     * @return array
     */
    public function  fetchArray($result) {
        return mysql_fetch_array($result);
    }

    /**
    * Devuelve el registro de un ResultSet en un array
    * @version: 1.0
    * @author: ironhandlord@gmail.com
    * @since: 2010-09-12 22:30
    * @param ResultSet rs
    * @return array
    */
    public function  fetchRow($result) {
        return mysql_fetch_row($result);
    }


    /**
     * Devuelve true si es un tipo cadena
     */
    
    public function isString($type){
        return in_array($this->getTypeName($type), $this->stringTypes);
    }

        
    /**
     * Devuelve verdadero si el tipo en del parametro es una cadena en mysql
     * date 2010-09-15 21:37
     * @author ironhandlord@gmail.com
     * @version 1.0
     * @param string type
     * @return Boolean
     */
    public function  myString($type) {
        $bool = false;
        $type = $this->getTypeName($type);
        switch (strtolower($type)){
            case 'char':
            case 'varchar':
            case 'binary':
            case 'varbinary':
            case 'tinyblob':
            case 'blob':
            case 'mediumblob':
            case 'longblob':
            case 'tinytext':
            case 'text':
            case 'mediumtext':
            case 'longtext':
            case 'enum':
            case 'date':
            case 'datetime':
            case 'time':
                $bool = true;
                break;
        }
        return $bool;
    }
    /**
     * Elimina los parentesis de una cadena que especifique un tipo de dato mysql
     * @version 1.0
     * @author ironhandlord@gmail.com
     * @param string tipo
     * @return string
     */
    private function getTypeName($strType){
        $strType = explode('(', $strType);
        return $strType[0];
    }
    
    /**
     * Imprime errores
     * @version 1.0
     * @since 2010-09-14
     * @author jvillegas@tuxteno.net
     * @param string message
     * @return string
     */

    private function MyError($msg=NULL) {
        echo "<u>MySQL Driver Error</u>:  $msg. <u>The message is</u>: ".  mysql_error($this->link);
    }

    public function getLastInsertId(){
        return mysql_insert_id($this->link);
    }

        /**
     * Destructor de la clase
     */
    public function  __destruct() {
        @mysql_close($this->link);
    }
}
?>
