<?php

/*
 * Class HTML Helpers
 */

class hlp extends EHtml{
    
    
    /**
     * return a Link tag
     * @param string $filename nombre del archivo (Sin extension)
     * @return string Tag
     */
    public static function link($filename){
        
        $properties = array('href'=>APPURL.'/css/'.$filename.'.css', 'rel'=>'stylesheet', 'type'=>'text/css');
        
        return self::tag('link', $properties, '', true);
        
    }
    
    /**
     * Carga una script del directorio /app/js/scriptname/ leyendo el archivo load.php
     * @param type $filename nombre del directorio de la script en /app/js/
     */
    public static function loadJScript($filename, $part=null){
        echo "\n";
        $scriptpath = APPDIR."/js/$filename/load.php";
        if( file_exists($scriptpath) ){
            include $scriptpath;
            if(isset($load)){
                if($part!=null){
                    $load = array($part => $load[$part]);
                }
                
                foreach ($load as  $type => $files){
                    switch ($type){
                        case 'css':
                            foreach ($files as $file){
                                $properties = array('href'=>APPURL.'/js/'.$filename.'/'.$file, 'rel'=>'stylesheet', 'type'=>'text/css');
                                echo self::tag('link', $properties, '', true), "\n";
                            }
                            break;
                        case 'js':
                            foreach ($files as $file){
                                $properties = array('src'=>APPURL.'/js/'.$filename.'/'.$file,  'type'=>'text/javascript');
                                echo self::tag('script', $properties), "\n";
                            }
                            break;
                    }
                }
            }
        }
        
    }
    
    /**
     * De acuerdo al controlador y la vista actual carga un archivo js  
     * si existe en /app/js/views/controllerName
     */
    public static function dynamicJs(){
//        if(file_exists($filename))
        $cnt = Engine::getControllerName();
        $act = Engine::getActionName();
        if(file_exists(self::strToPath(APPDIR."/js/views/global.js"))){
            $properties = array('src'=>APPURL."/js/views/global.js",  'type'=>'text/javascript');
            echo self::tag('script', $properties), "\n";
        }
        $basedir = self::strToPath(APPDIR."/js/views/$cnt/");
        if(file_exists(self::strToPath($basedir."general.js"))){
            $properties = array('src'=>APPURL."/js/views/$cnt/general.js",  'type'=>'text/javascript');
            echo self::tag('script', $properties), "\n";
        }
        if(file_exists(self::strToPath($basedir."$act.js"))){
            $properties = array('src'=>APPURL."/js/views/$cnt/$act.js",  'type'=>'text/javascript');
            echo self::tag('script', $properties), "\n";
        }
        
    }
    
    /**
     * De acuerdo al controlador y la vista actual carga un archivo css  
     * si existe en /app/css/views/controllerName
     */
    public static function dynamicCss(){
        $cnt = Engine::getControllerName();
        $act = Engine::getActionName();
        
        $basedir = self::strToPath(APPDIR."/css/views/$cnt/");
        
        if(file_exists(self::strToPath($basedir."$act.css"))){
            $properties = self::link("views/$cnt/$act");
            echo $properties, "\n";
        }
        
    }

    
        /**
     * HTML Anchor (&lt;a&gt;&lt;/a&gt;)
     * @param string $text Text to display
     * @param string $href Friendly Url  ( 'controller/action' )
     * @param array $properties associative array with  properties
     * @return string
     */
    public static function a($text, $href='', $properties = array('class'=>'')){
        $properties = array_merge(array('href'=> empty($href )? '#': self::parseUrl($href)), $properties);
        return self::tag('a', $properties, $text);
    }
    
    /**
     * HTML Form (&lt;form&gt;)
     * @param string $action Friendly Url  ( 'controller/action' )
     * @param string $method POST|GET
     * @param array $properties associative array with  properties
     * @return string
     */
    public static function form($action, $method='POST', $properties= array()){
        $properties = array_merge(array('action'=>  self::parseUrl($action), 'method'=>$method), $properties);
        return self::tag('form', $properties, null, true);
    }
    
    /**
     * End Of HTML Form (&lt;/form&gt;)
     * @return string end of form
     */
    public static function EOForm(){
        return self::tag('/form', array(), null, true);
    }
    
    /**
     * Devuelve una etiqueta label
     * @param string $fieldName Nombre del campo asociado
     * @param string $text Texto del label
     * @return string &lt;label&gt;
     */
    public static function label($fieldName, $text){
        return self::tag('label', array('for'=>$fieldName),$text);
    }
    
    /**
     * Devuelve una etiqueta input tipo hidden
     * @param string $fieldName Nombre del campo
     * @param string $value Valor del campo
     * @param array $properties Array asociativo para las propiedades html
     * @return string &lt;input type="hidden"&gt;
     */
    public static function inputHidden($fieldName, $value=''){
        return self::tag('input', array_merge(array('type'=>'hidden', 'id'=>$fieldName, 'name'=>$fieldName,'value'=>$value), array()), null, true);
    }
    
    /**
     * Devuelve una etiqueta input tipo text
     * @param string $fieldName Nombre del campo
     * @param string $value Valor del campo
     * @param array $properties Array asociativo para las propiedades html
     * @return string &lt;input type="text"&gt;
     */
    public static function inputText($fieldName, $value='', $properties=array()){
        return self::tag('input', array_merge(array('type'=>'text', 'id'=>$fieldName, 'name'=>$fieldName,'value'=>$value), $properties), null, true);
    }
    
    
    /**
     * Devuelve una etiqueta input tipo text
     * @param string $fieldName Nombre del campo
     * @param string $value Valor del campo
     * @param array $properties Array asociativo para las propiedades html
     * @return string &lt;input type="text"&gt;
     */
    public static function inputNumber($fieldName, $value='', $properties=array()){
        return self::tag('input', array_merge(array('type'=>'number', 'id'=>$fieldName, 'name'=>$fieldName,'value'=>$value), $properties), null, true);
    }
    
    /**
     * Devuelve una etiqueta input tipo text
     * @param string $fieldName Nombre del campo
     * @param string $value Valor del campo
     * @param array $properties Array asociativo para las propiedades html
     * @return string &lt;input type="text"&gt;
     */
    public static function inputPassword($fieldName, $value='', $properties=array()){
        return self::tag('input', array_merge(array('type'=>'password', 'id'=>$fieldName, 'name'=>$fieldName, 'value'=>$value), $properties), null, true);
    }
    
    /**
     * Devuelve una etiqueta input tipo text
     * @param string $fieldName Nombre del campo
     * @param string $value Valor del campo
     * @param array $properties Array asociativo para las propiedades html
     * @return string &lt;input type="text"&gt;
     */
    public static function inputFile($fieldName, $value='', $properties=array()){
        return self::tag('input', array_merge(array('type'=>'file', 'id'=>$fieldName, 'name'=>$fieldName, 'value'=>$value), $properties), null, true);
    }
    
    /**
     * Devuelve una etiqueta textarea
     * @param string $fieldName Nombre del campo
     * @param string $value valor
     * @param string $properties Array asociativo con las propiedades HTML
     * @return string &lt;textarea&gt;&lt;/textarea&gt;
     */
    public static function textArea($fieldName, $value='', $properties=array()){
        return self::tag('textarea', array_merge(array('id'=>$fieldName, 'name'=>$fieldName), $properties), $value);
    }
    
    /**
     * Devuelve una etiqueta select con options
     * @param string $fieldName Nombre del select
     * @param string $options Array de arrays que representan los option  array( array(1, 'Opcion', array(html properties)), ...);
     * @param string $properties Propiedades html de la etiqueta select
     * @return string &lt;select&gt;&lt;/select&gt;
     */
    public static function select($fieldName,  $options=array(), $properties=array()){
        $optTemplate = self::tag('option', array('value'=>'_VALUE_', '_PROP_'), '_TEXT_');
        $strOptions = '';
        foreach ($options as $option){
            $prop = '';
            if(isset($option[2])&&count($option[2])>0)
                foreach ($option[2] as $property=>$val)
                        $prop.= ' '.$property.'="'.$val.'"';
            
            if(isset($properties['value']) && $properties['value']==$option[0])
                $prop.= ' selected="selected"';
                
            $strOptions .= str_replace(array('_VALUE_', '_TEXT_', '_PROP_'), array($option[0], $option[1], $prop), $optTemplate);
        }
        
        return self::tag('select', array_merge( array('id'=>$fieldName,'name'=>$fieldName), $properties), $strOptions);
    }
    
    /**
     * Devuelve un o mas etiquetas input tipo radio 
     * @param string $fieldName Nombre del campo
     * @param string $options Array de arrays que representan los radios  array( array(1, 'Opcion', array(html properties)), ...);
     * @param string $properties Propiedades generales:<br>
     * class, separator
     * @return string &lt;input type="radio"&gt;
     */
    public static function inputRadio($fieldName,  $options, $properties=array()){
        $optTemplate = self::tag('input', array('type'=>'radio', 'id'=>$fieldName, 'name'=>$fieldName,
            'value'=>'_VALUE_', '_PROP_'), '_TEXT_', TRUE);
        
        $strOptions = '';
        foreach ($options as $option){
            $prop = '';
            if(isset($option[2])&&count($option[2])>0)
                foreach ($option[2] as $property=>$val)
                    $prop.= ' '.$property.'="'.$val.'"';
                
            if(isset($properties['value']) && $properties['value']==$option[0])
                $prop.= ' checked="checked"';
            
            $strOptions .= '<span class="'. (isset($properties['class'])? $properties['class']:'epradio')
            .'">'.str_replace(array('_VALUE_', '_TEXT_', '_PROP_'), array($option[0], $option[1], $prop), $optTemplate).'</span>'.
                ((isset($properties['separator'])? $properties['separator']:''));
        }
        
        return  $strOptions;
    }
    
    /**
     * Devuelve un o mas etiquetas input tipo checkbox 
     * @param string $fieldName Nombre del campo
     * @param string $options Array de arrays que representan los checkbox  array( array(1, 'Opcion', array(html properties)), ...);
     * @param string $properties Propiedades generales:<br>
     * class, separator
     * @return string &lt;input type="radio"&gt;
     */
    public static function checkBoxes($fieldName,  $options, $properties=array()){
        $optTemplate = self::tag('input', array('type'=>'checkbox', 'id'=>$fieldName,'name'=>$fieldName.'[]',
            'value'=>'_VALUE_', '_PROP_'), '_TEXT_', TRUE);
        
        $strOptions = '';
        foreach ($options as $option){
            $prop = '';
            if(isset($option[2])&&count($option[2])>0)
                foreach ($option[2] as $property=>$val)
                    $prop.= ' '.$property.'="'.$val.'"';
                
            $strOptions .= '<span class="'. (isset($properties['class'])? $properties['class']:'epradio')
            .'">'.str_replace(array('_VALUE_', '_TEXT_', '_PROP_'), array($option[0], $option[1], $prop), $optTemplate).'</span>'.
                ((isset($properties['separator'])? $properties['separator']:''));
        }
        
        return  $strOptions;
    }
    
    /**
     * Devuelve una etiqueta input tipo submit
     * @param string $value Valor a mostrar en el submit
     * @param string $name Nombre del submit
     * @param mixed $properties Array asociativo con las propiedades html
     * @return string
     */
    public static function submit($value, $name='sm', $properties=array()){
        return self::tag('input', array_merge(array('type'=>'submit', 'value'=>$value, 'id'=>$name, 'name'=>$name),$properties), null, true);
    }
    
    public static function inputButton($fieldName, $value='', $properties=array()){
        return self::tag('input', array_merge(array('type'=>'button', 'id'=>$fieldName, 'name'=>$fieldName,'value'=>$value), $properties), null, true);
    }
    
    public static function button($fieldName, $value='', $properties=array()) {
        return self::tag('button', array_merge(array('type'=>'submit', 'id'=>$fieldName, 'name'=>$fieldName), $properties), $value);
    }
    
    public static function img($filename, $properties=array()){
        $properties = array_merge(array('src'=>APPURL.'/img/'.$filename, 'alt'=>'image'),$properties);
        echo self::tag('img', $properties, '', true), "\n";
    }
    
}
    

?>
