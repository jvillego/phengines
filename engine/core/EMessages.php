<?php

class EMsg{
    
    public static function err($message, $title = 'Error: '){
        $str = '<div class="ui-widget">
                    <div class="ui-state-error ui-corner-all" style="padding: 0pt 0.7em;"> 
                        <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span> 
                        <strong>'.$title.'</strong> '.$message.'</p>
                    </div>
                </div>';
        echo $str;
    }
    
    public static function warn($message, $title = 'Warning: '){
        $str = '<div class="ui-widget">
                    <div class="ui-state-highlight ui-corner-all" style="padding: 0pt 0.7em;">
                            <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span>
                            <strong>'.$title.'</strong> '.$message.'</p>
                    </div>
                </div>';
        echo $str;
    }
    
    public static function info($message, $title = 'Notice: '){
        $str = '<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 0pt 0.7em;">
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span>
				<strong>'.$title.'</strong> '.$message.'</p>
			</div></div>';
        echo $str;
    }
}

?>
