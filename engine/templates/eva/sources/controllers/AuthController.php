<?php

/**
 * Controlador para el manejo de la autenticación
 */

use Firebase\JWT\JWT;

class AuthController extends EngineApplication {
    
    public static $template = 'Main';
    
    
    public static function __GET__() {
        
        $result = array(
            'success'=>true,
            'method'=>'GET'
        );
        
        
        return $result;
    }

    /**
     * Autenticar
     */
    public static function __POST__($username, $password) {
        $result = array();
//        error_log("\nAutenticando usuario". "\n", 3, "/tmp/error.log");
//        error_log(var_export($username, true). "\n", 3, "/tmp/error.log");
//        error_log(var_export($password, true). "\n", 3, "/tmp/error.log");
        
        
        $username = self::test_input($username);
        if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $ierr++;
            $aerr['usuario']="Nombre de usuario o contraseña incorrecto.";
        }
        
        $password = self::test_input($password);
        
        $usr = users::login($username, $password);
        
//        error_log("usuario consulltado:". "\n", 3, "/tmp/error.log");
//        error_log(var_export($usr, true). "\n", 3, "/tmp/error.log");
        
        if(isset($usr->iduser) && $usr->iduser> 0 ){
            
            $result['token'] = JWTAuth::SignIn(array(
                'iduser'=>$usr->iduser,
                'username'=>$usr->username,
                'status'=>$usr->status,
            ));
            
//            error_log("token generado para el usuario {$usr->username}:". "\n", 3, "/tmp/error.log");
//            error_log($result['token']. "\n", 3, "/tmp/error.log");
            
        }else{
            $result['success'] = 'false';
            $result['Nombre de usuario o contraseña incorrecto.'];
        }
        
        
//        error_log(var_export($result, true). "\n", 3, "/tmp/error.log");
            
        return $result;
    }
    
    
    
}