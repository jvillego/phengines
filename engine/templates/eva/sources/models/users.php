<?php
/**
 * Modelo usuarios
 * Los usuarios del sistema
 */

use Firebase\JWT\JWT;

class users extends EActiveRecord{
    
    
    public static $__alias = 'us';
    
    public static function attributeLabels(){
        return array(
        );
    }
    
    /**
     * Se configuran las relaciones con tablas a las cuales les entrega la pk
     * @return array
     */
    public static function relations(){
        return array(
            'table1'=>array('table1.pk'=>'table2.pkp'),
        );
    }
    
    /**
     * Se configuran las claves que recibe de otras tablas
     * @return array
     */
    public static function foreign(){
        return array(
        );
    }
    
    
   /* 
    public static function login($username,$password) {
        $aux = self::model()
                ->where('username =', $username)
                ->andWhere('password =', $password)
                ->andWhere('status = "enabled"')
                ->fetchOne();
        
        return $aux;
    }
    */
    public static function validate($token) {
        
        try{
            JWTAuth::Check($token);
            return true;
        }  catch (Exception $e){
           console::error("JWT Validation exception");
        }
        
    }
}


