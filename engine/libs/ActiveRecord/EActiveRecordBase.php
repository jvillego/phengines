<?php

/*
 * Active Record Base
 * 
 */
class EActiveRecordBase extends ESQLBuilder{
    
//    const INNER = 1;
//    const LEFT = 2;
//    const RIGHT = 3;
    
    private static $instances = array();

    protected $model;
    
    protected $modeldesc;

    protected $DB = null;
    
    public $methods = array();
    public $relatedModels = array();
    
    public function __construct() {
        $this->DB = new EDB();
        $this->DB->model = get_called_class();
        $this->model = $this->DB->model;

        //Mapeamos la base de datos
        $this->modeldesc = $this->DB->describeModel();
        foreach ($this->modeldesc as $field){
            if(!isset($this->{$field['Field']})) //TODO: revisar las implicaciones de poner esta condicion, se pone para no resetear los valores si ya existen
                $this->{$field['Field']} = is_null($field['Default'])? null:$field['Default'];
        }
        
        //Mapeamos los metodos dinamicos
        if(method_exists($this, 'relations')){
            $rels = array_keys($this->relations());
            if(count($rels)>0){
                $this->relatedModels = array_merge($this->relatedModels, $rels);
            }
        }
        if(method_exists($this, 'foreign')){
            $rels = array_keys($this->foreign());
            if(count($rels)>0){
                $this->relatedModels = array_merge($this->relatedModels, $rels);
            }
        }
        $this->joinStack[] = $this->model;
        
    }
    
    public $joinStack = array();
    
    /**
     * Permite realizar los Join dinamicos
     * @param type $name
     * @param type $arguments array(0=>JoinType, 1=alias)
     * @return EActiveRecordBase 
     */
    public function __call($name, $arguments) {
        //Arg[0] = JoinType
        //Arg[1] = temporal alias
        if(in_array($name, $this->relatedModels)){
            $this->joinStack[] = $name;
            EModel::load($name);
            if(count($arguments)>1)
                $this->addJoin($this->model, isset($arguments[0])?$arguments[0]:self::INNER, array($name=>$arguments[1]));
            else
                $this->addJoin($this->model, isset($arguments[0])?$arguments[0]:self::INNER, $name);

            $this->select();
        }else{
//            $lastModel = end($this->joinStack);
            //Pregunta si se le indico en el 3er parametro la tabla con la que debe hacer join, si no toma la ultima del stak
            $lastModel = isset($arguments[2])&&!empty($arguments[2])? $arguments[2]: end($this->joinStack);
            EModel::load($lastModel);
            
            $relatedModels = array();
            if(method_exists($lastModel, 'foreign')){
                $rels = array_keys($lastModel::foreign());
                if(count($rels)>0){
                    $relatedModels = array_merge($relatedModels, $rels);
                }
            }
            if(method_exists($lastModel, 'relations')){
                $rels = array_keys($lastModel::relations());
                if(count($rels)>0){
                    $relatedModels = array_merge($relatedModels, $rels);
                }
            }
            
            if(in_array($name, $relatedModels)){
                $this->joinStack[] = $name;
                EModel::load($name);
                if(count($arguments)>1)
                    $this->addJoin($lastModel, isset($arguments[0])?$arguments[0]:self::INNER, array($name=>$arguments[1]));
                else
                    $this->addJoin($lastModel, isset($arguments[0])?$arguments[0]:self::INNER, $name);
                
                $this->select();
            }
        }
        
        return $this;
    }

        /**
     * Returns model instance (SINGLETON)
     */
    protected static function model(){ 
        $class = get_called_class();// User class
        if(!isset(self::$instances[$class])){
            self::$instances[$class] = new $class;
            self::$instances[$class]->model = $class;
        }
        
        return self::$instances[$class];
    }
    
    
    private function catchVars($aFields){
        $fields = array();
        
        if(is_null($aFields))
            $fields = $this->getValidModelAttrs($this);//get_object_vars($this)
        else $fields = $this->getValidModelAttrs($aFields);
        
        return $fields;
    }

        /**
     * Inserta un registro en el modelo
     * @since 2011-07-02
     * @author jvillegasosorio@gmail.com
     * @version 2.0
     * @return Boolean
     */
    public function insert($aFields = null) {

        $sql = $this->buildInsertQuery($this->catchVars($aFields));
        
//        return $sql;
        $this->DB->execQuery($sql);
    }
    
    
    /**
     * Actualiza un registro del modelo
     * @since 2011-07-02
     * @author jvillegasosorio@gmail.com
     * @version 2.0
     * @return Boolean
     */
    public function update($aFields = null) {
        
        $sql = $this->buildUpdateQuery($this->catchVars($aFields));
        
        //return $sql;
        $this->DB->execQuery($sql);
    }
    
    /**
     * Elimina un registro del modelo
     * @since 2011-07-07
     * @author jvillegasosorio@gmail.com
     * @version 2.0
     * @return Boolean
     */
    public function delete($aFields = null) {
        
        $sql = $this->buildDeleteQuery($this->catchVars($aFields));
        
        //return $sql;
        $this->DB->execQuery($sql);
    }
    

    public function executeQuery(){
        $sql = $this->buildSelectQuery();
        return $this->DB->query($sql);
    }
    
    public function rawQuery($sql){
        $this->setQuery($sql);
        return $this;
    }

    public function getLastInsertId(){
        return $this->DB->getLastInsertId();
    }

        ///Relations
    
    public function getRelations(){
        return $this->relations();
    }
    
    
    //Attributes
    public function getAttrLabel($attribute){
        if(method_exists($this, 'attributeLabels')){
            $intersected = array_intersect_key($this->attributeLabels(), $this->modeldesc);
            return isset($intersected[$attribute])? $intersected[$attribute]: $attribute;
        }else{
            return $attribute;
        }
    }
    
    
    public function getModelDesc(){
        return $this->modeldesc;
    }
    
}
    
?>
