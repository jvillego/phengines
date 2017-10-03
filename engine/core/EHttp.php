<?php

/*
 * Engine HTTP
 * Esta es para el manejo de las variables HTTP
 */

class EHttp extends EBaseRun{
    
    public static function getMethod() {
//        return filter_input('INPUT_SERVER', 'REQUEST_METHOD'); //TODO: revisar esta forma de capturar la info
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * Devuelve el valor GET o el array GET completo de acuerdo al parametro
     * @param string var nombre de la variable GET
     */
    public static function htget($var = null){
        return is_null($var)? $_GET : ( isset($_GET[$var])? $_GET[$var] : $_GET );
    }
    
    /**
     * Devuelve el valor POST o el array POST completo de acuerdo al parametro
     * @param string var nombre de la variable POST
     */
    public static function htpost($var = null){
        return is_null($var)? $_POST : ( isset($_POST[$var])? $_POST[$var] : $_POST );
    }
    
    /**
     * Devuelve el request uri de la variable $_SERVER
     * @param boolean $rawString true Para devolver la url en forma de string, false para devolver en forma de array
     * @return mixed 
     */
    public static function getUrl(){
        return $_SERVER['REQUEST_URI'];
    }
    
    
    public static function getVariables(){

        $vars = $_REQUEST;
        
        //if post or put has body content
        if(in_array(self::getMethod(), array('POST', 'PUT')) ){
            $body_content = file_get_contents('php://input');
            if(!empty($body_content)){
                $auxvars = json_decode($body_content, true);
                if(!is_null($auxvars)){
                    $vars = array_merge($vars, $auxvars);
                }else{
                    $vars['data'] = $body_content;
                }
            }
        }
        
        return $vars;
    }

    /**
     * Parsea una url Amigable
     */
    protected static function parseUrl($furl){
        $aurl = explode('/', $furl);
        switch(count($aurl)){
//            case 1: return 'index.php?controller='.Engine::$_controller.'&action='.(empty($aurl[0])?'#':$aurl[0]); break;
            case 0: return '?'.Engine::$_controller.'/'.Engine::$_action;break;
            case 1: return '?'.Engine::$_controller.'/'.(empty($aurl[0])?'#':$aurl[0]); break;
            default :
                return '?'.$furl;
//            case 2: return '?'.$furl;break;
//            case 2: return '?'.$aurl[0].'/'.$aurl[1];break;
//             
        }
    }
}

?>
