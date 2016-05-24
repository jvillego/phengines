<?php

/**
 * Constructor de SQL
 */

class ESQLBuilder{

    //JOIN TYPES
    const INNER = ' INNER '; //RELATED
    const RIGHT = ' RIGHT '; //ALL
    const LEFT = ' LEFT '; //NOT_RELATED
    
    private $sql;

    private $userQuery = false;
    private $sel;
    private $set;
    private $from;
    private $join;
    private $where;
    private $order;
    private $group;
    private $limit;
    private $lastField;
    
    protected $rawQuery = false;

    /**
     * Valida de un objeto o array los campos que pertenecen al modelo y devuelve un array asociativo con los valores
     */
    public function getValidModelAttrs($source){
        
        return array_intersect_key( is_array($source)? $source : get_object_vars($source), $this->modeldesc);
        
//        $fields = array();
//        foreach ($source as $field=> $value){
//            if(in_array($field, $modelfields))
//                $fields[$field] = $value;
//        }
//        
//        return $fields;
    }
    
    
    /**
     * Devuelve un valor encerrado entre comillas si es un campo string
     */
    private function isStringField($field){
        $fld = explode('.', $field);
        $fld = $fld[count($fld)-1];
        return $this->DB->isString(@$this->modeldesc[$fld]['Type']);//TODO: aqui sale un php notice cuando se hace un join
    }

        //TODO: Esta funcion deberia devolver un boleano... la operacion se realiza en la que hace el llamado.
    public function encloseString($field, $value){
        return $this->isStringField($field)? '"'.$value.'"' : $value ;
    }

    /**
     * devuelve un array con los valores tipo string entre comillas
     */
    public function encloseStrings($record, $ignoreEmptyFields){
        $record = $ignoreEmptyFields? array_filter($record): $record;
        $frecord = array();
        foreach ($record as $field=>$value){
//            $frecord[$field] = $this->DB->isString($this->modeldesc[$field]['Type'])? '"'.$value.'"' : $value ;
            $frecord[$field] = $this->encloseString($field, $value);
        }
        return $frecord;
    }

    /**
     * Construye una consulta insert
     */
    public function buildInsertQuery($assocfields, $ignoreEmptyFields = true){
        $this->sql = 'INSERT INTO '.$this->model.'(';
        
        $columns = $ignoreEmptyFields? array_filter($assocfields): $assocfields;
        $titles = array_keys($columns);
        $tc = count($titles);
        for($i=0;$i<$tc;$i++)
            $titles[$i] = "`{$titles[$i]}`";
            
        $this->sql.= implode(',', $titles).') ';
        $this->sql.= 'VALUES ('.implode(',', $this->encloseStrings($assocfields, $ignoreEmptyFields)).') ';
        return $this->sql;
    }
    
    
    private static function getKeys($key){//Filter
        return (($key['Key'] == 'PRI')? true:false);
    }

    /**
     * Retorna un array con los campos de la PK
     */
    public function getPK(){
        $apk = array_filter($this->modeldesc, 'ESQLBuilder::getKeys');
        return $apk;
    }
    
    
    /**
     * filtro para validar los ceros en array_filter
     */
    private static function admitZero($val){//Filter
        return strlen( (string) $val )>0? true : false;
    }

    /**
     * Construye una consulta update
     */
    public function buildUpdateQuery($assocfields, $ignoreEmptyFields = true){

        
        $this->sql = 'UPDATE '.$this->model.' SET ';
        
        if($this->userQuery){
            $from = !is_null($this->from)?str_replace("FROM", "",$this->from):$this->model; ///TODO:  el str_replace se puede omitir poniendo el FROM en otro momento...
            $this->sql = 'UPDATE '.$from.' SET ';
            $this->sql .= $this->set;
            if(!is_null($this->where)){
                $this->sql .= $this->where;
            }
            $this->userQuery=false;
            $this->set = '';
            $this->where = '';
            return $this->sql;
        }
        
        
        
        
        $columns = $ignoreEmptyFields? array_filter($assocfields, 'ESQLBuilder::admitZero'): $assocfields; 
        
        $pk =  $this->getPK();
        /*TODO: validar que la sentencia SET si tenga campos para actualizar. Implementar clase manejo errores*/
        $updatefields = array_diff_key($columns, $pk);//Diferente a la PK
        
        $updates = array();
        foreach ($updatefields as $field => $value ){
            $updates[] = '`'.$field.'`='.$this->encloseString($field, $value).' ';
        }
        
        $this->sql.= implode(', ', $updates);
        
        $criteria = array_intersect_key($columns, $pk);//Solo la PK
        
        if(count( array_filter($criteria, 'ESQLBuilder::admitZero')) > 0){
            
            $this->sql.=' WHERE ';
            
            $condition = array();
            foreach ($criteria as $field => $value ){
                $condition[] = $field.'='.$this->encloseString($field, $value).' ';
            }
            
            $this->sql.= implode(' and ', $condition);
        }
        
        return $this->sql;
    }
    
    
    /**
     * Construye una consulta delete
     */
    public function buildDeleteQuery($assocfields, $ignoreEmptyFields = true){

        $this->sql = 'DELETE FROM '.$this->model.' ';
        
        if($this->userQuery){
            if(!is_null($this->where)){
                $this->sql .= $this->where;
            }
            $this->userQuery=false;
            $this->where = '';
            return $this->sql;
        }
        
        $columns = $ignoreEmptyFields? array_filter($assocfields, 'ESQLBuilder::admitZero'): $assocfields; 
        
        $pk =  $this->getPK();
                
        $criteria = array_intersect_key($columns, $pk);//Solo la PK
        
        if(count( array_filter($criteria, 'ESQLBuilder::admitZero')) > 0){
            
            $this->sql.=' WHERE ';
            
            $condition = array();
            foreach ($criteria as $field => $value ){
                $condition[] = $field.'='.$this->encloseString($field, $value).' ';
            }
            
            $this->sql.= implode(' and ', $condition);
        }
        
        return $this->sql;
    }
    
    
    /**
     * Funciones para la construccion del sql por el usuario
     * 
     */
    
    private function getVarName($string){
        $pattern = '/^[A-Z"_"a-z\.][A-Z"_"a-z0-9\.]*/';
        preg_match($pattern, $string, $matches);
        return $matches[0];
    }


    public function set($params){
        $this->userQuery = true;
        
        $aset = array();
        foreach ($params as $field => $value){
            $aset[] = $field.'='.$this->encloseString($field, $value);
        }
        $this->set .= implode(', ', $aset);
        
        return $this;
    }
    
    
    private function createCondition($nargs, $args, $prefix = ''){
        $field = null;
        if($nargs == 1){
            $field = $this->getVarName($args[0]);
            $this->where .= $prefix .$args[0];
            //TODO: Implemartar una forma que permita generar condiciones anidadas  ->nestedCondition pude ser una clase criteria.
        }elseif($nargs == 2){
            $field = $this->getVarName($args[0]);
            if(is_array($args[1])){
                $strGlue = $this->isStringField($field);
                $glue = $strGlue? '","': ',';
                $this->where .= $prefix .$args[0]. ' ('.($strGlue?'"':'') . implode($glue, $args[1]) . ($strGlue?'"':''). ') ';
            }else{
                $this->where .= $prefix .$args[0].' '.$this->encloseString($field, $args[1]);
            }
        }elseif($nargs == 3){
            $pattern = '/:(\w+)[\w ]+:(\w+)/';
            $field = $this->getVarName($args[0]);
            preg_match($pattern, $args[0], $matches);
            $this->where .= $prefix. str_replace(array(':'.$matches[1], ':'.$matches[2]),
                    array($this->encloseString($field, $args[1]),$this->encloseString($field, $args[2])), $args[0]);
        }
        $this->lastField = $field;
    }

    public function where(){
        $this->userQuery = true;
        $this->createCondition(func_num_args(), func_get_args(), ' WHERE ');
        return $this;
    }
    
    ///TODO: Implementar un tercer argumento para la seguridad contra ataques (array o valor validado)
    public function andWhere(){
        $this->createCondition(func_num_args(), func_get_args(), ' AND ');
        return $this;
    }
    
    public function orWhere(){
        $this->createCondition(func_num_args(), func_get_args(), ' OR ');
        return $this;
    
    }
    
    public function select(){
        $this->userQuery = true;
        $this->sel = ' SELECT '.( func_num_args() > 0? implode(', ',func_get_args()) : ' * '). $this->from();
        return $this;
    }
    
    public function order(){
        $args = func_get_args();
        $this->order = ' ORDER BY '.( func_num_args() > 1? implode(', ',$args) : $args[0] ). ' ';
        return $this;
    }
    
    public function group(){
        $args = func_get_args();
        $this->group = ' GROUP BY '.( func_num_args() > 1? implode(', ',$args) : $args[0] ). ' ';
        return $this;
    }
    
    public function limit(){
        $args = func_get_args();
        $this->limit = ' LIMIT '.( func_num_args() > 1? implode(', ',$args) : $args[0] ). ' ';
        return $this;
    }
    
    private function from(){
        if( !is_null($this->join) ){
            $this->from = ' FROM ' . $this->join .' ';
        }else $this->from = ' FROM '.$this->model. ' ';
        
        return $this->from;
    }

    public function addJoin($model1, $joinType, $model2) {// o.O
        $alias1 = null;
        $alias2 = null;
        if(is_array($model1)){
            $alias1 = current($model1);
            $model1 = key($model1);
        }
        if(is_array($model2)){
            $alias2 = current($model2);
            $model2 = key($model2);
        }
        
//        if(method_exists($model1, 'foreign') && method_exists($model2, 'relations') ){
        if((method_exists($model1, 'foreign')&& array_key_exists($model2,  $model1::foreign())) 
                && (method_exists($model2, 'relations') && array_key_exists($model1, $model2::relations())) ){
            $this->join .=  (empty($this->join)? 
                $this->model .' '.(   !is_null($alias1)? $alias1 :  (isset($model1::$__alias)? $model1::$__alias:'')  ) . ' ': ' ')
                . $joinType . ' JOIN ' 
                . $model2 . ' '.( !is_null($alias2)? $alias2 : (isset($model2::$__alias)? $model2::$__alias:'')  );
            
            $references = $model1::foreign();
            $aON = array();
            $this->join.= ' ON ';
            foreach ($references[$model2] as $fk=>$key){
                $aON[]= (   !is_null($alias2)? $alias2 :  (isset($model2::$__alias)?$model2::$__alias:$model2)   ).'.'.$key. 
                             ' = '.(   !is_null($alias1)? $alias1 : (isset($model1::$__alias)?$model1::$__alias:$model1)  ).'.'.$fk;
            }
            $this->join .= implode(' AND ', $aON);
            
//        }elseif(method_exists($model1, 'relations') && method_exists($model2, 'foreign')){
        }elseif((method_exists($model1, 'relations') && array_key_exists($model2, $model1::relations()) )
                && (method_exists($model2, 'foreign') && array_key_exists($model1, $model2::foreign()))){//de 1-n
            $this->join .=  (empty($this->join)? $this->model .' '.( isset($model1::$__alias)? $model1::$__alias:'') . ' ': ' ')
                . $joinType . ' JOIN ' . $model2 . ' '.( isset($model2::$__alias)? $model2::$__alias:'');
            $references = $model1::relations();
            $aON = array();
            $this->join.= ' ON ';
            foreach ($references[$model2] as $fk=>$key){
                $aON[]= (isset($model2::$__alias)?$model2::$__alias:$model2).'.'.$key. 
                             ' = '.(isset($model1::$__alias)?$model1::$__alias:$model1).'.'.$fk;
            }
            $this->join .= implode(' AND ', $aON);
        }else{
            die("ENGINEPHP::Models FATAL ERROR: No se han establecido correctamente las relaciones entre las tablas $model1 y $model2");//TODO: implementar manejo de exeptions
        }    
    }

    
    public function buildSelectQuery(){
        if(!$this->rawQuery){
            $this->sql = is_null($this->sel)? ' SELECT * '.$this->from() : $this->sel.' ';

            if(!is_null($this->group)){
                    $this->where .= $this->group;
            }

            if(!is_null($this->order)){
                $this->where .= $this->order;
            }

            if(!is_null($this->limit)){
                $this->where .= $this->limit;
            }

            $this->sql .= $this->where;


            $this->userQuery=false;
            $this->where = '';
            $this->join = null;
            $this->sel = null;
            $this->order = '';
            $this->limit = '';
        }else $this->rawQuery = false;
        return $this->sql;
    }


//    public function in($array){
//        $strGlue = $this->isStringField($this->lastField);
//        $glue = $strGlue? '","': ',';
//        $this->where .= ' IN ('.($strGlue?'"':'') . implode($glue, $array) . ($strGlue?'"':''). ') ';
//        return $this;
//    }
//    
//    public function notIn($array){
//        $strGlue = $this->isStringField($this->lastField);
//        $glue = $strGlue? '","': ',';
//        $this->where .= ' NOT IN ('.($strGlue?'"':'') . implode($glue, $array) . ($strGlue?'"':''). ') ';
//        return $this;
//    }

    
    protected function setQuery($sql){
        $this->rawQuery = true;
        $this->sql = $sql;
    }

    
    /**
     * Retorna la ultima sql generada TODO: no se si dejar esta funcion
     */
    public function getLastSql(){
        return $this->sql;
    }
}
?>
