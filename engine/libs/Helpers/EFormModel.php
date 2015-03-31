<?php
/**
 * Clase para manejar los campos de un modelo en forma de campos html
 * @author jvillego  <jvillegasosorio@gmail.com>
 */

class EFormModel extends EFormHelper{
    
    private $fields;
    private $ignoreFields;
    private $buttons = array(
//        'Aceptar'=>array('type'=>'submit', 'name'=>'sm'),
//        'Cancelar'=>array('type'=>'button', 'name'=>'cancel'),
    );

    public function __construct($model) {
        if(is_object($model)){
            $this->modeldesc = $model->getModelDesc();
            if(method_exists($model, 'attributeLabels')){
                foreach ($this->modeldesc as $field => $vals){
                    $this->modeldesc[$field]['label'] = $model->getAttrLabel($field);
                    if(isset($model->{$field})){
                        $this->modeldesc[$field]['value'] = $model->{$field};
                    }
                }
            }
            $this->fields = array_keys($this->modeldesc);
        }
        $this->ignoreFields = array();
        
    }
    public function setLabel($label) {
        $this->modeldesc[$this->currentField]['label'] = $label;
        return $this;
    }
   
    public function ignoreFields($array){
        if(is_array($array))
            $this->ignoreFields = $array;
    }
    
    public function showForm($action, $method='POST', $properties= array()){
        echo $this->form($action, $method, $properties);
        foreach ($this->modeldesc as $field => $attrs){
            if(in_array($field, $this->ignoreFields)) continue;
            
            echo '<div id="EFormModelField">';
            if($this->$field()->getType()!='inputHidden'){
                
                echo "<div>", hlp::label($field, isset($attrs['label'])?$attrs['label']:$field), ":</div>";
            }
            
            echo "<div>", $this->$field(isset($attrs['value'])?$attrs['value']:null)->show(),"</div>";
            
            echo '</div>';
        }
        if(count($this->buttons)>0){
            echo '<hr />';
            echo '<div class="EFormModelButtons">';
            foreach ($this->buttons as $caption=>$btn){
                if(isset($btn['type'])){
                    switch ($btn['type']){
                        case 'button':
                            echo hlp::inputButton($caption,$caption,$btn);
                            break;
                        case 'link':
                            $btn['class']='jqbtn';
                            echo hlp::a($caption,'#',$btn);
                            break;
                        default:
                            echo hlp::submit($caption, 'sm', $btn);
                    }
                }else{
                    echo hlp::submit($caption, 'sm', $btn);
                }
                echo '&nbsp; ';
            }
            echo '</div>';
        }
        echo $this->endForm();
    }
    
    public function setButtons($abuttons) {
        if(is_array($abuttons)){
            $this->buttons = $abuttons;
        }
    }
    
    
}
?>
