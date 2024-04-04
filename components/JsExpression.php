<?php

namespace app\components;

use yii\helpers\ArrayHelper;

/**
 * Description of JsExpr
 *
 * @author demiurg
 */
class JsExpression extends \yii\web\JsExpression {
    public function __construct(string $expression, array $bindings = [], array $config = []) {
        parent::__construct($this->_bind($expression, $bindings), $config);
    }

    protected function _obj($key, $value): string {
        return $this->_n7e($key) . ':' . $this->_n7e($value);
    }

    protected function _n7e($value): string {
        $_value = null;
        $type = gettype($value);
        switch ($type) {
        case 'object':
        case 'string':
            $_value = "'" . addslashes(strval($value)) . "'";
            break;
        case 'integer':
        case 'float':
        case 'double':
            $_value = $value;
            break;
        case 'boolean':
            $_value = $value ? 'true' : 'false';
            break;
        case 'NULL':
            $_value = 'null';
            break;
        case 'array':
            if (ArrayHelper::isIndexed($value)) {
                $_cb = fn($value) => $this->_n7e($value);
                $_value = '[' . join(', ', array_map($_cb, $value)) . ']';
            }
            else {
                $_cb = fn($key, $value) => $this->_obj($key, $value);
                $_data = [array_keys($value), array_values($value)];
                $_value = '{' . join(', ', array_map($_cb, ...$_data)) . '}';
            }
            break;
        default:
            throw new \Exception("Unknown type: {$type}");
        }
        return $_value;
    }

    protected function _bind($expression, $bindings): string
    {
        do {
            if (!count($bindings)) {
                break;
            }
            $callback = function ($name) {
                if (substr($name, 0, 1) !== '$') {
                    throw new \Exception("The binding name must start with a dollar sign: {$name}");
                }
                return '\\' . $name;
            };
            $keys = array_map($callback, array_keys($bindings));
            $pattern = "/" . join('|', $keys) . "/";
            $matches = [];
            $res = preg_match_all($pattern, $expression, $matches, PREG_OFFSET_CAPTURE);
            if (!$res) {
                throw new \Exception('No one of the listed bindings was found in the expression');
            }
            usort($matches[0], fn($a, $b) => $b[1] <=> $a[1]);
            foreach ($matches[0] as $match) {
                list($name, $offset) = $match;
                try {
                    $value = $this->_n7e($bindings[$name]);
                    $expression = substr_replace($expression, $value, $offset, strlen($name));
                }
                catch (\Exception $e) {
                    throw new \Exception("Raised error for binding: {$name}", 0, $e);
                }
            }
        }
        while (false);

        return $expression;
    }
}
