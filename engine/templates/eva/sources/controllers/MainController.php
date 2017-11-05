<?php

/**
 * Interfaz principal / Inicial
 */

class MainController extends EngineApplication{
    
    public static $template = 'Main';
    
    
    public static function __GET__() {
        
        
        
        $result = array(
            'success'=>true,
            'application'=>Engine::getAppName(),
            'version'=>'1.0.0'
        );
        
        
        
        
        return $result;
    }

    public static function start(){
        
    }
    
    
}
