<?php

/*
 * Class Engine HTML
 */

class EHtml extends EHttp{
    
    
    /**
     * Crea una etiqueta HTML
     * @author ironhandlord
     * @param string $tagName Name of HTML tag
     * @param array $properties array with al HTML properties of the tag
     * @param string $value Value enclosed by tag
     * @param boolean $inline validates if the tag have a end of tag
     * @return string An string with the formed tag
     */
    protected static function tag($tagName, $properties, $value='', $inline = false){
        $tag = '<'.$tagName;
        if(is_array($properties))
        foreach ($properties as $property=>$val){
            
            $tag.= !is_string($property)? $val : ' '.$property.'="'.$val.'"';
        }
        elseif(is_string($properties))
            $tag.=$properties;
        $tag.='>'.($inline?$value:"$value</$tagName>");
        return $tag;
    }
    
}
?>
