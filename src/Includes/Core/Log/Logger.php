<?php

namespace Wladweb\Tweettee\Includes\Core\Log;

use Wladweb\Tweettee\Includes\Core\Exceptions\TweetteeAdminException;
use Wladweb\Tweettee\Includes\Core\Exceptions\TweetteePublicException;
use Wladweb\Tweettee\Includes\Core\TweetteeApp;

/**
 * Logger show errors in admin part, write errors in file in public part 
 */
class Logger
{
    public static function handle(\Exception $e)
    {
        if ($e instanceof TweetteeAdminException){
            return self::handle_admin($e);
        } elseif ($e instanceof TweetteePublicException) {
            return self::handle_public($e);
        } else {
            return;
        }
    }
    
    private static function handle_admin($e)
    {
        $output = '<h3 class="error_header">Error!</h3>';
        
        $json = json_decode($e->getMessage(), true);
        
        if (!$json){
            $output .= '<b>Code:</b> ' . $e->getCode() . ' <b>Message:</b> ' . $e->getMessage() . '<br>';
            return $output;
        }
        
        foreach ($json['errors'] as $error) {
            $output .= '<b>Code:</b> ' . $error['code'] . ' <b>Message:</b> ' . $error['message'] . '<br>';
        }
        
        return $output;
    }
    
    private static function handle_public($e)
    {
        $line =  '!!!Exception. Code: ' . $e->getCode() . '. Message: ' . $e->getMessage();
        self::write($line);
    }
    
    public static function write($line)
    {
        $log_file = \plugin_dir_path(TweetteeApp::$plugin_dir_path) . 'tweettee.log';
        
        if (file_exists($log_file) && !is_writable($log_file)){
            return;
        }
        
        $date = new \DateTime;
        $time = $date->format('d-M-Y H:i:s');
        
        $fh = fopen($log_file, 'a');
        
        $line = $time . " -- " . $line . "\r\n"; 
        
        \fwrite($fh, $line);
        \fclose($fh);
    }
}
