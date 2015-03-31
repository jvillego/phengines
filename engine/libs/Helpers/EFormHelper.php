<?php
/**
 * Clase para manejar los campos de un modelo en forma de campos html
 * @author jvillego  <jvillegasosorio@gmail.com>
 */

class EFormHelper{
    
    protected $modeldesc;
    protected $currentField;
    
    public function __call($name, $value) {
        $this->currentField = $name;
        $this->modeldesc[$name]['type'] = !empty($this->modeldesc[$name]['type'])?$this->modeldesc[$name]['type']:'inputText';
        $this->modeldesc[$name]['value'] = 
                isset($value[0])?$value[0] : (isset($this->modeldesc[$name]['value'])? $this->modeldesc[$name]['value']:null);

        $this->modeldesc[$name]['attributes'] = !isset($this->modeldesc[$name]['attributes'])? array():$this->modeldesc[$name]['attributes'];
        return $this;
    }
    
    public function input($type){
        $this->modeldesc[$this->currentField]['type'] = !empty($type)? 'input'. ucfirst($type) : 'inputText';
        return $this;
    }
    
    public function textArea($attributes = array()){
        $this->modeldesc[$this->currentField]['type'] = 'textArea';
        if(is_array($attributes)&& count($attributes)>0 )
            $this->modeldesc[$this->currentField]['attributes'] = $attributes;
        return $this;
    }
    
    public function select($options, $attributes  = array()){
        $this->modeldesc[$this->currentField]['type'] = 'select';
        $this->modeldesc[$this->currentField]['options'] = is_array($options)? $options : array();
        
        if(is_array($attributes)&& count($attributes)>0 )
            $this->modeldesc[$this->currentField]['attributes'] = $attributes;
        
        return $this;
    }
    
    public function checkboxes($options, $attributes  = array()){
        $this->modeldesc[$this->currentField]['type'] = 'checkbox';
        $this->modeldesc[$this->currentField]['options'] = is_array($options)? $options : array();
        
        if(is_array($attributes)&& count($attributes)>0 )
            $this->modeldesc[$this->currentField]['attributes'] = $attributes;
        
        return $this;
    }
    
    public function radios($options, $attributes  = array()){
        $this->modeldesc[$this->currentField]['type'] = 'radio';
        $this->modeldesc[$this->currentField]['options'] = is_array($options)? $options : array();
        
        if(is_array($attributes)&& count($attributes)>0 )
            $this->modeldesc[$this->currentField]['attributes'] = $attributes;
        
        return $this;
    }

    public function attributes($attributes){
        if(is_array($attributes))
            $this->modeldesc[$this->currentField]['attributes'] = $attributes;
        return $this;
    }

    public function attr($attr, $value){
        if(!empty($attr)){
            $this->modeldesc[$this->currentField]['attributes'][$attr] = $value;
        }
        return $this;
    }
    
    public function getAttr($attr){
        if(!empty($attr)){
            return $this->modeldesc[$this->currentField]['attributes'][$attr];
        }
        return null;
    }
    public function getType(){
        return $this->modeldesc[$this->currentField]['type'];
    }

    public function draw(){
        $html = '';
        switch ($this->modeldesc[$this->currentField]['type']){
            case 'inputNumber':
                $html = hlp::inputNumber($this->currentField, $this->modeldesc[$this->currentField]['value'], $this->modeldesc[$this->currentField]['attributes']);
                break;
            case 'inputHidden':
                $html = hlp::inputHidden($this->currentField, $this->modeldesc[$this->currentField]['value']);
                break;
            case 'inputPassword':
                $html = hlp::inputPassword($this->currentField,$this->modeldesc[$this->currentField]['value'],$this->modeldesc[$this->currentField]['attributes']);
                break;
            case 'textArea':
                $html = hlp::textArea($this->currentField,$this->modeldesc[$this->currentField]['value'],$this->modeldesc[$this->currentField]['attributes']);
                break;
            case 'select':
                $this->modeldesc[$this->currentField]['attributes']['value'] = $this->modeldesc[$this->currentField]['value'];
                $html = hlp::select($this->currentField,$this->modeldesc[$this->currentField]['options'],$this->modeldesc[$this->currentField]['attributes']);
                break;
            case 'checkbox':
                $html = hlp::checkBoxes($this->currentField,$this->modeldesc[$this->currentField]['options'],$this->modeldesc[$this->currentField]['attributes']);
                break;
            case 'radio':
                $html = hlp::inputRadio($this->currentField,$this->modeldesc[$this->currentField]['options'],$this->modeldesc[$this->currentField]['attributes']);
                break;
            default:
            case 'inputText':
                $html = hlp::inputText($this->currentField, $this->modeldesc[$this->currentField]['value'], $this->modeldesc[$this->currentField]['attributes']);
                break;
            
        }
        return $html;
    }
    
    public function show(){
        echo $this->draw();
    }
    
    public function form($action, $method='POST', $properties= array()){
        return hlp::form($action, $method, $properties);
    }
    public function endForm(){
        return hlp::EOForm();
    }
}
?>
