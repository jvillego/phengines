<?php

/**
 * Clase para gestionar las vistas
 */
class EView extends EBaseRun{
    
    public static $template = null;
    public static $view = null;
    
    public static $ctrlout = null;
    
    
    private static $variables = array();


    public static function getTemplate(){
        $ctemplate = is_null(self::$template)? self::arrayToPath(array(APPDIR, 'sources', 'views', 'defaultTemplate.php') )
        : self::arrayToPath(array(APPDIR, 'sources', 'views', self::$template, 'template.php'));
        
        
        if(file_exists($ctemplate)){
            include($ctemplate);
        }  else {
            //LOG: Aqui debe hacer log de que no encontro ninguna plantilla
            self::getView();
        }
    }
    
    public static function getView(){
        //Imprimimos la salida que se da durante la ejecucion del action
        echo Engine::$stdout;
        
        if(count(self::$variables)>0){
            $vars = self::$variables;
            extract($vars);
        }
        
        $view = self::arrayToPath(array(APPDIR, 'sources', 'views', Engine::$_controller, self::$view.'.php'));
        
        if(file_exists($view)){
            include($view);
        }
    }
    
    public static function appendVars($assocArray){
        self::$variables = array_merge(self::$variables, $assocArray);
    }
    
    /**
     * Permite renderizar una vista dentro de otra
     * @param type $url url amigable
     * @param type $variables Array asociativo con las variables para la vista renderizar
     */
    public static function renderPartial($url, $variables=null){
        $view = self::strToPath(APPDIR."/sources/views/$url.php");
        if(file_exists($view)){
            if(is_array($variables))
                extract ($variables);
            
            include($view);
        }
    }
}
?>
