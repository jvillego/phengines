<?php

/*
 * Base application execution
 */
class EBaseRun{
    
    /**
     * Devuelve un path tomando como parametro un array
     * @param string array Array de nombre de directorios y archivos
     * @return string Path
     */
    public static function arrayToPath($array){
        return implode(DIRECTORY_SEPARATOR, $array);
    }
    
    /**
     * Devuelve un path teniendo en cuenta la separacion de directorios segun el SO
     * @param string $string string con el path
     * @return string Path
     */
    public static function strToPath($string){
        if(DIRECTORY_SEPARATOR != '/')
            return str_replace('/', DIRECTORY_SEPARATOR, $string);
        else return $string;
    }
}
?>
