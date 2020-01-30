<?php

use yii\helpers\VarDumper;

/**
 * Дебагер
 * @param mixed $var
 */
function dpm($var)
{
    if (YII_DEBUG) {
        $msg = VarDumper::dumpAsString($var, 10, true);
        $trace = debug_backtrace();
        if (isset($trace[0])) {
            $msg .= '<div class="pull-right">' . $trace[0]['file'] . ' ' . $trace[0]['line'] . '</div>';
        }
        Yii::$app->session->addFlash('info', $msg);
    }
}

if (!function_exists('array_key_first')) {
    function array_key_first(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
}