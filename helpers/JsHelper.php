<?php

namespace app\helpers;

/**
 * Description of JsHelper
 *
 * @author demiurg
 */
class JsHelper {
    /**
     * 
     * @param yii\web\View $view
     * @param array $vars
     */
    public static function setVars($view, $vars) {
        $js = "if(!window.jsVars||typeof window.jsVars==='object'){window.jsVars={};}";
        foreach ($vars as $key => $value) {
            if (!preg_match('/^[a-z_]+[a-z0-9-_]*$/i', $key)) {
                continue;
            }
            $js .= "window.jsVars.{$key} = " . (json_encode($value) ?: 'null') . ';';
        }
        
        $view->registerJs($js, View::POS_HEAD);
    }
}
