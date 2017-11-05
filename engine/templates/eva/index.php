<?php

/**
 * EnginePHP Application
 * 
 */
header('Content-Type: text/html; charset=UTF-8');
define('ENGINE', '../engines/engine/');

//$time_start =  microtime(true);

include(ENGINE . 'core/Engine.php');
Engine::run();


//$time_end =  microtime(true);
//$time = $time_end - $time_start;
//
//echo '<hr><div style="margin-top:10px; font-size: 10px;">App ExecutionTime: ', substr($time,0,6), '</div>';
?>
