<?php

/**
 * Clase para la gestion del cache
 * TODO: El cache debe tener encuenta el usuario logueado y crear un directorio por cada uno
 * TODO: 
 */

abstract class ECache extends EBaseRun{
    
    private static $hash;
    private static $cacheFile;
    
    private static $cache;
    
    private static $staticCache = false;


    public static function getHash($url){
        self::$hash = md5($url);
        return self::$hash;
    }
    
    public static function haveCache(){
        self::$cacheFile = self::strToPath( APPDIR . '/sources/runtime/cache/'. self::$hash);
        if(file_exists(self::$cacheFile)){
            return true;
        }
        
        return false;
    }
    
    public static function getCache(){
        include self::$cacheFile;
    }
    
    
    public static function startCache(){
        self::$cache = null;
        ob_start();
    }
    
    public static function endCache(){
        self::$cache = ob_get_contents();
        ob_end_clean();
    }
    
    public static function saveCache(){
        $hf = fopen(self::$cacheFile, 'w');
        fwrite($hf, self::$cache);
        fclose($hf);
        self::$cache = null;
    }
    
    public static function getFileName(){
        return self::$cacheFile;
    }
    
    public static function staticCache($mode = true){
        self::$staticCache = true;
    }
    
    public static function isStatic(){
        return self::$staticCache;
    }
}
?>
