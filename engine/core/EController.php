<?php

/*
 * Clase base para los controladores
 * 
 */
class EController extends EHttp{
    
    /**
     * Mostrar la plantilla o no
     * @var Boolean $showTemplate 
     */
    public static $showTemplate = true;
    /**
     * Template a mostrar
     * @var Boolean $template
     */
    public static $template = null;
    /**
     * Archivo View a mostrar
     * @var Boolean $view
     */
    public static $view = null;
    
    
    /**
     * Permite indicar si los metodos REST devuelven una respuesta automaticamente
     * @var Boolean 
     */
    public static $rest_enable_auto_response = true;
    
    /**
     * Agrega una variable para la vista
     */
    public static function setVar($strVarname, $value){
        EView::appendVars(array($strVarname=>$value));
    }
    
    /**
     * Agrega una lista variables para la vista
     */
    public static function setVars($assocArray){
        EView::appendVars($assocArray);
    }
    
    
    public static function redirect($url){
        header('Location: ?'.$url);
    }
    
    /**
     * Permite indicar que un action es estatico para cuando el cache esta activado
     */
    public static function isStatic(){
        ECache::staticCache();
    }
}
?>
