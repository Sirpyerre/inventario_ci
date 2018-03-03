<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Función para debugear un array o cualquier cosa y ponerlo bonito
 */
if ( ! function_exists('Debug')) {
    function dd( $array = array(),$message = '', $exit = true)
    {
        $trace = debug_backtrace();
        $file  = isset($trace[0]['file']) ? $trace[0]['file'] : 'Unknow';
        $line  = isset($trace[0]['line']) ? $trace[0]['line'] : 'Unknow';

        $class    = isset($trace[1]['class']) ? $trace[1]['class'] : '';
        $function = isset($trace[1]['function']) ? $trace[1]['function'] : '';

        if (is_array($array)) {
            echo '<pre>*************<b>' . $message . '</b>***************<br>';
            echo '<p style="color:red">';

            print_r($array);

            echo '</p>';
            echo '</pre>';
            if (!empty($class)) {
                echo '=========&gt; ' . $class . '::' . $function . '&lt;=========<br>';

            }
        } else if (is_object($array)) {
            echo "=========&gt; $message &lt;=========";

            var_dump($array);

            echo " &lt;=========";
            if (!empty($class)) {
                echo '=========&gt; ' . $class . '::' . $function . '&lt;=========<br>';
            }

        } else {
            echo '<div style="width: 60%"><code>';
            echo "=========&gt; $message &lt;=========<br>";
            print_r($array);
            echo '</code></div>';
        }

        echo 'Line[' . $line . '], in file ' . $file .'<br/><br/>';
        if ($exit) {
            exit();
        }
    }
}