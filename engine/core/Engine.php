<?php

/*
 * EnginePHP 
 * Main class
 */


define('ROOTDIR', dirname(dirname(dirname(__FILE__))));

require ROOTDIR . DIRECTORY_SEPARATOR.'engine'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'EBaseRun.php';

define('COREDIR', EBaseRun::strToPath(ROOTDIR . '/engine/core'));

require EBaseRun::strToPath(COREDIR . '/EMessages.php');

//define('APPDIR', str_replace('/', DIRECTORY_SEPARATOR, dirname($_SERVER["SCRIPT_FILENAME"]))) ;
define('APPDIR', getcwd()) ;

define('APPURL', dirname($_SERVER['SCRIPT_NAME']));

include ROOTDIR .'/engine/libs/Console.php';

if(class_exists('console')){
    
    console::configure(array(
       'logfile'=>'/tmp/error.log',
       'appname'=>'engines',
       'loglevel'=>'trace'
    ));
    
}

class Engine extends EBaseRun{
    
    public static $_controller;
    public static $_action;
    
    private static $engine_config = array();
    private static $config = array();
    
    public static $stdout;

    
    
    public static function start(){
    
        $CPATH = EBaseRun::strToPath(ROOTDIR . '/engine/config/config.ini.php');
        if(is_file($CPATH)){
            self::$config = parse_ini_file($CPATH, true);
            
        }else {
            EMsg::err ('Failed to load engine configuration, file no found');
            die();
        }
		$appname = self::$config['run']['defaultApp'];
        if(file_exists("$appname/")){
			header('location: '. APPURL . '/' .$appname. '/');
		}else{
			echo "<h1>enginePHP Framework: Aplication \"$appname\" don't exists.</h1>";
			echo "<p>Edit \"config/config.ini.php\" to change default application name</p>";			
		}
    }
    
    
    /**
     * Ejecuta la aplicacion
     */
    public static function run(){
        
        $CPATH = self::strToPath(APPDIR . '/sources/config/config.ini.php');
        
        if(file_exists($CPATH)){
            
            self::$engine_config = parse_ini_file(self::strToPath(ROOTDIR.'/engine/config/config.ini.php'), true);
            self::$config = parse_ini_file($CPATH, true);

            
            if(isset(self::$config['run'])){
                require self::strToPath(COREDIR . '/EHttp.php');

                $variables = EHttp::getVariables();
                
                self::$_controller = (isset($variables['__ctrl'] ) && $variables['__ctrl']!=null)? $variables['__ctrl'] : self::$config['run']['defaultController'];
                              
                ///OBTENEMOS LAS VARIABLES
                
                
                $ControllerPath = self::strToPath( APPDIR . '/sources/controllers/'. self::$_controller.'Controller.php');
                
                if(file_exists($ControllerPath)){
                    
                    //Cargamos las clases necesarias
                    require self::strToPath(COREDIR . '/EController.php');
                    EController::$_request_vars = $variables;
                    
                    require self::strToPath(APPDIR . '/sources/base/EngineApplication.php');
                    //Cargamos el controlador
                    require $ControllerPath;
                    
                    $controller =  self::$_controller . 'Controller' ;
                    
                    if(isset($variables['__act']) && $variables['__act']!=null && method_exists($controller, $variables['__act']) ){
                        self::$_action = $variables['__act'];
                    }elseif(self::$config['run']['enableRest'] && method_exists($controller, '__'.EHttp::getMethod().'__')){
                        self::$_action = '__'.EHttp::getMethod().'__';
                        $controller::$showTemplate = false;
                    }else{
                        self::$_action = self::$config['run']['defaultAction'];
                    }
                    
                    
                    unset($variables['__ctrl']);
                    unset($variables['__act']);
                    unset(EController::$_request_vars['__ctrl']);
                    unset(EController::$_request_vars['__act']);
                    
                    //Execute action
                    //@todo use exceptions
                    if( method_exists($controller, self::$_action) ){
                        
                        //Cargamos las clases para las vistas
                        
                        //TODO: no se si sea correcto... pero aqui inicio las sesiones.. esto mantendria vivas las sessiones
                        @session_start();
                        //Capturamos la salida durante la ejecucion del action
                        if(!isset($_SESSION[sha1(self::getAppName())])){
                            $_SESSION[sha1(self::getAppName())]=array('uid'=>null);
                        }
                        ob_start();
                        
                        
                        //Precargamos las librerias basicas para los modelos
                        /*TODO: Crear drivers para PostgreSql, SQLServer, Oracle*/
                        require self::strToPath(COREDIR . '/EModel.php');
                        switch(self::$config['database']['engine']){
                            case 'mysql':
                                /*TODO: Verificar si es buena idea cargarlas aqui o  en el model*/
                                require self::strToPath(ROOTDIR.'/engine/libs/ActiveRecord/drivers/EDBDriver.php');
                                require self::strToPath(ROOTDIR.'/engine/libs/ActiveRecord/drivers/'.self::$config['database']['engine'].'/EDB.php');
                                require self::strToPath(ROOTDIR.'/engine/libs/ActiveRecord/ESQLBuilder.php');
                                require self::strToPath(ROOTDIR.'/engine/libs/ActiveRecord/EActiveRecordBase.php');
                                require self::strToPath(ROOTDIR.'/engine/libs/ActiveRecord/EActiveRecord.php');
                                break;
                        }
                        
                        //Aqui cargamos las librerias comunes
                        $commonLibs = array();
                        if(isset(self::$engine_config['preload'])){
                            if(isset(self::$engine_config['preload']['libs'])){
                                $commonLibs = explode(',', self::$engine_config['preload']['libs']);
                                foreach ($commonLibs as $klib => $lib){
                                    $filename = self::strToPath(ROOTDIR."/engine/libs/$lib.php");
                                    if(file_exists($filename)){
                                        require $filename;
                                    }
                                }
                            }
                        }
                        //Aqui cargamos las librerias de usuario visibles en los controladores
                        if(isset(self::$config['preload'])){
                            if(isset(self::$config['preload']['libs'])){
                                $alibs = explode(',', self::$config['preload']['libs']);
                                foreach ($alibs as $klib => $lib){
                                    if(!in_array($lib, $commonLibs)){
                                        $filename = self::strToPath(APPDIR."/sources/libs/$lib.php");
                                        if(file_exists($filename)){
                                            require $filename;
                                        }
                                    }elseif(!empty($lib)){
                                        EMsg::warn("No se puede cargar la librer&iacute;a $lib.php porque ya se carg&oacute; una librer&iacute;a com&uacute;n con el mismo nombre de archivo. Por favor revise que no sea la misma, o simplemente ren&oacute;mbrela.",'ENGINEPHP::PRELOAD WARNING');
                                    }
                                }
                            }
                        }
                        
                        //CARGAR MODELS
                        if(self::$config['run']['autoLoadModels'])//carga automatica de todos los modelos
                            EModel::autoLoad();
                        elseif(method_exists($controller, 'localModels')){//carga semi automatica en el controller
                            EModel::load($controller::localModels());
                        }
                        if(method_exists($controller, 'globalModels')){//Carga modelos a nivel global
                            EModel::load($controller::globalModels());
                        }
                        
                        //CALLBACKS
                        if(method_exists($controller, 'beforeAction')){
                            try{
                                
                                $actionReturn = forward_static_call_array(array($controller, 'beforeAction'), $variables);
                                
                                
                            }  catch (Exception $e){
                                //add log library
                            }
                        }
                        
                        //EXECUTE ACTION
                        $action = self::$_action;
                        require self::strToPath(COREDIR  . '/EView.php');

                        $actionReturn = array();
                        if(self::$config['run']['enableCache']){
                            require self::strToPath(ROOTDIR.'/engine/libs/ECache.php');
                            ECache::getHash(EHttp::getUrl(true));
                            
                            if(ECache::isStatic()){//No tiene cache?
                                if(!ECache::haveCache()){
                                    $actionReturn = forward_static_call_array(array($controller, $action), $variables);
                                }
                            }else{
                                $actionReturn = forward_static_call_array(array($controller, $action), $variables);        
                            }
                        }else {
                            $actionReturn = forward_static_call_array(array($controller, $action), $variables);
                        }

                        
                        //Asignamos variables a la vista (solo si es un array)
                        if(is_array($actionReturn)){ EView::appendVars ($actionReturn);}
                        //Obtenemos el contenido de la salida
                        Engine::$stdout = ob_get_contents();
                        ob_end_clean();
                        
                        EView::$template = $controller::$template;
                        EView::$view = !is_null($controller::$view)? $controller::$view : $action;
                        

                        ///AQUI APLICAMOS EL CACHE
                        require self::strToPath(ROOTDIR.'/engine/libs/Helpers/EHtml.php');
                        require self::strToPath(ROOTDIR.'/engine/libs/Helpers/EHelpers.php');
                        require self::strToPath(ROOTDIR.'/engine/libs/Helpers/EFormHelper.php');
                        require self::strToPath(ROOTDIR.'/engine/libs/Helpers/EFormModel.php');
                        
                        //AquI cargamos las librerias de usuario visibles a nivel de vistas
                        $alibs = glob(APPDIR."/sources/libs/*.views.php");
                        foreach($alibs as $lib){
                            include_once $lib;
                        }
                        if(self::$config['run']['enableCache'] && !self::$config['run']['enableRest']){
                            
                            if(ECache::isStatic()){
                                if(!ECache::haveCache()){
                                    ECache::staticCache(false);

                                    ECache::startCache();
                                    if($controller::$showTemplate){
                                        EView::getTemplate();
                                    }else{
                                        EView::getView();
                                    }
                                    ECache::endCache();

                                    ECache::saveCache();
                                } 
                                
                                ECache::getCache();

                            }else{
                                if($controller::$showTemplate){
                                    EView::getTemplate();
                                }else{
                                    EView::getView();
                                }
                            }
                            
                            
                            
                        }else{
                            if($controller::$showTemplate){
                                EView::getTemplate();
                            }elseif(self::$config['run']['enableRest']){
                                if(in_array(self::$_action, array('__GET__', '__POST__', '__PUT__', '__DELETE__'))){
                                    if ($controller::$rest_enable_auto_response){
                                        header('Content-Type: application/json');
                                        echo json_encode($actionReturn);
                                    }else{
                                        EView::getView();
                                    }
                                }else{
                                    EView::getView();
                                }
                            }else{
                                EView::getView();
                            }

                        }
                        
                        
                        
                    }else {
                        EMsg::err('El m&eacute;todo "'.self::$_action.'" de la clase "'.self::$_controller.'" no fue encontrado.');
                    }
                    
                }  else {
                    EMsg::err('El controlador "'.self::$_controller.'" no fue encontrado.');
                }
                
            }else{
                EMsg::err('El archivo de configuraci&oacute;n no tiene la seccion [run]', 'ENGINEPHP CRASH!: ');
            }
            
        }else{
            EMsg::err('No se encontr&oacute; el archivo de configuraci&oacute;n', 'ENGINEPHP CRASH!: ');
            exit(1);
        }
        
    }
    
    /**
    * Retorna el valor de una variable en post o get.
    *
    *  @version  1.0
    *  @author: ironhandlord@gmail.com
    *  @param  string  $varname nombre de la variable a acceder
    *  @return mixed | null
    */
    public static function request($varname){
        return isset($_POST[$varname])? $_POST[$varname]: (isset($_GET[$varname])?$_GET[$varname]:null);
    }
    
    /**
     * Devuelve el array de configuracion
     */
    public static function getConfig(){
        return self::$config;
    }
    /**
     * Devuelve un dato del array de configuracion
     */
    public static function getConfigData($section,$data){
        return self::$config[$section][$data];
    }
    
    /**
     * Devuelve el nombre de la accion actual
     * @return string action Name
     */
    public static function getActionName(){
        return self::$_action;
    }    
    
    /**
     * Devuelve el nombre del controlador actual
     * @return string controller Name
     */
    public static function getControllerName(){
        return self::$_controller;
    }    
    
    public static function getAppName(){
        return isset(self::$config['application']['appname'])? self::$config['application']['appname']:'EnginePHP Application';
    }
    public static function getAppVersion(){
        return isset(self::$config['application']['version'])? self::$config['application']['version']:'1.0';
    }
}


?>
