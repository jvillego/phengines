<?php

/*
 * Model Manager
 */

class EModel extends EBaseRun{
    
    /**
     * Carga modelos manualmente
     * @param mixed array string modelo o array de modelos
     */
    public static function load($models){ //Factory
        $models = is_array($models)? $models : array($models);
        foreach ($models as $model){
            $modelpath = self::strToPath(APPDIR . '/sources/models/'.$model.'.php');
            $mdl = null;
            if(file_exists( $modelpath )){
                include_once $modelpath;
            }else{
                EMsg::err('No se encontr&oacute; el modelo <b><u>'.$model.'</u></b>. Debe ser creado en <b><u>'.$modelpath.'</u></b>');
                exit(1);
            }
        }
    }
    
    
    /**
     * Carga automaticamente todos los modelos existentes 
     */
    public static function autoLoad(){
        $models = glob(APPDIR . '/sources/models/*.php');
        foreach ($models as $model){
            include_once $model;
        }
    }
}

?>
