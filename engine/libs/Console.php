<?php
/**
* Esta clase permite realizar el log en un archivo
*/

class console{
	
	public static $pathFile;
        
        private static $logLevel;
        
        private static $format;

        const LOG_LEVE_TRACE = 1; //
        const LOG_LEVE_DEBUG = 2;
        const LOG_LEVE_SUCCESS = 3;
        const LOG_LEVE_INFO = 4;
        const LOG_LEVE_WARN = 5;
        const LOG_LEVE_ERROR = 6;
        const LOG_LEVE_LOG = 7;

        private static $colors = false;
        
        public static function configure($config){
		
		self::$pathFile = $config['logfile'];
                self::$format = $config['appname'];
                
                switch ($config['loglevel']){
                    case 'trace':
                            self::$logLevel = self::LOG_LEVE_TRACE;
                        break;
                    case 'debug':
                            self::$logLevel = self::LOG_LEVE_DEBUG;
                        break;
                    case 'success':
                            self::$logLevel = self::LOG_LEVE_SUCCESS;
                        break;
                    case 'info':
                    case 'information':
                    case 'notice':
                            self::$logLevel = self::LOG_LEVE_INFO;
                        break;
                    case 'warn':
                    case 'warning':
                            self::$logLevel = self::LOG_LEVE_WARN;
                        break;                    
                    case 'error':
                            self::$logLevel = self::LOG_LEVE_ERROR;
                        break;
                    
                }
		
	}

	public static function log($mensaje, $useFormat=false){
            
                error_log( ($useFormat? date('Y-m-d H:i:s').' - '. self::$format." - " : '') . $mensaje."\n", 3, self::$pathFile);
	
	}
        
        /**
         * Debug and tracing messages
         * @param string $mensaje
         */
        public static function trace($mensaje) {
            
            if(self::$logLevel <= self::LOG_LEVE_TRACE){
                error_log( "\033[1;37m" . date('Y-m-d H:i:s').' - '.self::$format." - TRACE: ".$mensaje."\033[0m\n", 3, self::$pathFile);
            }
            
        }
        
        /**
         * Debug and tracing variables
         * @param string $mensaje
         */
        public static function debug($mensaje, $variabe) {
            
            if(self::$logLevel <= self::LOG_LEVE_TRACE){
                error_log( "\033[0;37m" . date('Y-m-d H:i:s').' - '.self::$format." - DEBUG: ".$mensaje . ": ".var_export($variabe, true)."\033[0m\n", 3, self::$pathFile);
            }
            
        }
        
        
        
        /**
         * Success messages
         * @param string $mensaje
         */
        public static function success($mensaje) {
            
            if(self::$logLevel <= self::LOG_LEVE_INFO){
                error_log( "\033[1;32m" . date('Y-m-d H:i:s').' - '.self::$format." - SUCCESS: ".$mensaje."\033[0m\n", 3, self::$pathFile);
            }
            
        }
        
       
        
        
        /**
         * For business logic information
         * @param string $mensaje
         */
        public static function info($mensaje) {
            
            if(self::$logLevel <= self::LOG_LEVE_INFO){
                error_log( "\033[1;36m" . date('Y-m-d H:i:s').' - '.self::$format." - INFO: ".$mensaje."\033[0m\n", 3, self::$pathFile);
            }
            
        }
        
        /**
         * For warning messages
         * @param string $mensaje
         */
        public static function warn($mensaje) {
            
            if(self::$logLevel <= self::LOG_LEVE_WARN){
                error_log( "\033[1;33m" . date('Y-m-d H:i:s').' - '.self::$format." - WARNING: ".$mensaje."\033[0m\n", 3, self::$pathFile);
            }
            
        }
        
        /**
         * For swarning messages
         * @param string $mensaje
         */
        public static function warning($mensaje) {
            
            if(self::$logLevel <= self::LOG_LEVE_WARN){
                error_log( "\033[1;33m" . date('Y-m-d H:i:s').' - '.self::$format." - WARNING: ".$mensaje."\033[0m\n", 3, self::$pathFile);
            }
            
        }
        
        /**
         * For error messages
         * @param string $mensaje
         */
        public static function error($mensaje) {
            
            if(self::$logLevel <= self::LOG_LEVE_ERROR){
                error_log( "\033[1;31m" . date('Y-m-d H:i:s').' - '.self::$format." - ERROR: ".$mensaje."\033[0m\n", 3, self::$pathFile);
            }
            
        }
        

}
