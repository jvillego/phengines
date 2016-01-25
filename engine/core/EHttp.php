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
     * Devuelve la url amigable
     * @param boolean $rawString true Para devolver la url en forma de string, false para devolver en forma de array
     * @return mixed 
     */
    public static function getUrl($rawString = false){
        $keys = key($_GET);
        $sUrl = empty($keys)? key($_POST): key($_GET);
        return $rawString? $keys : explode('/',$sUrl);
    }
    
    
    public static function getVariables($url, $restUrl = false){
        //Obtenemos las variables amigables
            // controller/action/var1/val/var2/val/var3/val
        $vars = array();
        $tv = count($url);
//        $start = $restUrl? 1:2;
        if($tv>2){
//            for($i = 2; $i< $tv;$i+=2){
            for($i = 2; $i< $tv;$i++){ //se omite el uso de nombres de variables cuando se pasan por url. El
//                $vars[$url[$i]] = $url[$i+1];
                $vars[] = $url[$i];
            }
        }
        
        //Obtenemos las variables post o get
        if(count($_GET)>2 || count($_POST)>0){
            $vars  = array_merge($vars, $_POST); //array_merge($vars, $_GET)
        }
        
        if($restUrl){
            $method = self::getMethod();
            if($method == 'POST' || $method == 'PUT'){
                $vars[] = file_get_contents('php://input');
            }else{
                $vars = array();
                for($i = 1; $i< $tv;$i++){
                    $vars[] = $url[$i];
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
