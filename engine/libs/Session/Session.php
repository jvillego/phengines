<?php
/*
 * Clase para gestionar las sesiones de usuario
 */
class Session extends EActiveRecord{

    public static function start($aValues) {
        if(is_array($aValues)){
            foreach ($aValues as $var => $val){
                $_SESSION[sha1(Engine::getAppName())][$var] = $val;
            }
        }
    }
    
    public static function logout(){
        unset($_SESSION[sha1(Engine::getAppName())]);
    }
    
    
    public static function getUserId(){
        return $_SESSION[sha1(Engine::getAppName())]['uid'];
    }
    
    public static function getUserType(){
        return $_SESSION[sha1(Engine::getAppName())]['usertype'];
    }
    
    public static function getUserName(){
        return $_SESSION[sha1(Engine::getAppName())]['user'];
    }
    
    /**
     * Crea una variable de sesion
     * @param string $varname nombre de la variable
     * @param string $varvalue valor de la variable
     */
    public static function setVar($varname, $varvalue){
        $_SESSION[sha1(Engine::getAppName())][$varname] = $varvalue;
    }
    
    /**
     * Devuelve el valor de una variable de sesion
     * @param string $varname nombre de la variable
     * @return mixed
     */
    public static function getVar($varname) {
        return $_SESSION[sha1(Engine::getAppName())][$varname];
    }
    
    /**
     * Elimina una variable de sesion
     * @param string $varname nombre de la variable
     */
    public static function unsetVar($varname) {
        if(isset($_SESSION[sha1(Engine::getAppName())][$varname]))
            unset($_SESSION[sha1(Engine::getAppName())][$varname]);
    }
    
    
}



?>
