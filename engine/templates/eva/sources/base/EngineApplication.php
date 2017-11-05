<?php

/*
 * Esta clase provee metodos y variables  para todos los controladores
 * 
 */
class EngineApplication extends EController{
    
    /**
     * permite establecer si una accion requiere autenticacion para ser ejecutada
     * @var 
     */
    public static $unauthentictedActions = array(
        'Auth'=>array('__POST__'),
        'Main'=>array('__GET__', 'start'),
    );
    
    public static function requireAuthentication($controller, $view) {
        
        $required = true;
        
        if(isset(self::$unauthentictedActions[$controller])){
            if(in_array($view, self::$unauthentictedActions[$controller])){
                $required = false;
            }
        }
        
        return $required;
        
    }
    
    public static function beforeAction() {
        //Valida la sesión del usuario
        /**
        * CORS CONFIGURATION:
        **/
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
        $request_headers        = apache_request_headers();
        $request_headers        = $_SERVER['HTTP_HOST'];

        $http_origin            = $request_headers;
        
        $allowed_http_origins   = array(
                                    "localhost"  ,
                                    "facebook.com"  ,
                                );
        if (in_array($http_origin, $allowed_http_origins)){  
            @header("Access-Control-Allow-Origin: " . $http_origin);
        }
        
        
        $headers = getallheaders();
        
        $cname = Engine::getControllerName();
        $aname = Engine::getActionName();
        if(self::requireAuthentication($cname, $aname)){ //si no es authcontroller identifiquese
            
            if(isset($headers['Authorization']) ){
                list($type, $token) = explode(' ', $headers['Authorization']);

                error_log("El token es:". "\n", 3, "/tmp/error.log");
                error_log($token. "\n", 3, "/tmp/error.log");
                if(!users::validate($token)){
                    echo json_encode(array(
                        'success'=>false,
                        'message'=>'Access denied: Invalid token'
                    ));
                    exit();
                }
                
            }else{
                echo json_encode(array(
                    'success'=>false,
                    'message'=>'Access Denied: Not authenticated'
                ));
                exit();
            }
            
        }
        
        
        

    }
    
    public static function checkDate($date) {
        $time = strtotime($date);
        if($time){
            $y = date('Y', $time);
            $m = date('m', $time);
            $d = date('d', $time);            
            return checkdate($m, $d, $y);
        }
        
        return false;
    }

    
    //validation:
    public static function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
     
    
}
