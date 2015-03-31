<?php

/*
 * Active Record Base
 * 
 */
class EActiveRecord extends EActiveRecordBase{
    
    /**
     * Para indicar que retorne un array de objetos
     */
    const OBJECT_RESULT = 0;
    /**
     * Para indicar que retorne un array de objetos extendidos de la clase del modelo
     */
    const MODEL_EXTENDED_RESULT = 3;

    /**
     * Devuelve Un Objecto desde la base datos como resultado de una busqueda
     * @param int $mode (opcional, default=0) resultado  0=Objetos, 1=MYSQL_ASSOC, 2=MYSQL_NUM, 3=Objetos extendidos de su clase
    | */
    public function fetchOne($mode = 0){
        $rs = $this->executeQuery();
        return mysql_fetch_object($rs, $mode==self::MODEL_EXTENDED_RESULT?$this->model:'stdClass');
    }
    
    /**
     * Devuelve un array desde la base de datos como resultado de una busqueda
     * @param int $mode (opcional, default=0) resultado  0=Objetos, 1=MYSQL_ASSOC, 2=MYSQL_NUM, 3=Objetos extendidos de su clase
     * @return type 
     */
    public function fetchAll($mode = 0){
        $ars = array();
        $rs = $this->executeQuery();
//        while($obj = $mode == 0 ? mysql_fetch_object($rs): mysql_fetch_array($rs, $mode)){
        //TODO: documentar la extension de clase model o stdClass
        while($obj = $mode == 0 || $mode==3 ? mysql_fetch_object($rs, $mode==3?$this->model:'stdClass'): mysql_fetch_array($rs, $mode)){
            $ars[]=$obj;
        }
        
        return $ars;
    }
    
    
    public function count(){
        $this->select('count(1) as ttal');
        $rw = $this->fetchOne();
        return $rw->ttal;
    }
    
    
    public function exists(){
        return ($this->count() > 0)? true : false;
    }
    
    
    public static function affectedRows() {
        return mysql_affected_rows();
    }
}

?>
