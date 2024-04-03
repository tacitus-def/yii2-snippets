<?php

namespace app\components;

use yii\helpers\ArrayHelper;

/**
 * Description of JsExpr
 *
 * @author demiurg
 */
class JsExpr {
    protected $_expression = '';
    protected $_bindings = [];
    protected $_cached;

    public function __construct(string $expression, array $bindings = []) {
        $this->_expression = $expression;
        foreach ($bindings as $name => $value) {
            $this->set($name, $value);
        }
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
                $_value = '[' . join(', ', array_map(fn($value) => $this->_n7e($value), $value)) . ']';
            }
            else {
                $_value = '{' . join(', ', array_map(fn($key, $value) => $this->_obj($key, $value), array_keys($value), array_values($value))) . '}';
            }
            break;
        default:
            throw new \Exception("Unknown type of binding: $type");
        }
        return $_value;
    }

    public function get(string $name) {
        return $this->_bindings[$name] ?? null;
    }

    public function set(string $name, $value): void {
        if (substr($name, 0, 1) !== ':') {
            throw new \Exception('The binding name must start with a colon');
        }
        $this->_cached = null;
        $this->_bindings[$name] = $value;
    }

    public function delete(string $name): void {
        $this->_cached = null;
        unset($this->_bindings[$name]);
    }

    public function __toString(): string
    {
        do {
            if ($this->_cached !== null) {
                break;
            }

            $_expr = $this->_expression;

            if (!count($this->_bindings)) {
                $this->_cached = $_expr;
                break;
            }
            $keys = array_keys($this->_bindings);
            $pattern = "/" . join('|', $keys) . "/";
            $matches = [];
            $res = preg_match_all($pattern, $_expr, $matches, PREG_OFFSET_CAPTURE);
            if (!$res) {
                throw new \Exception('No one of the listed bindings was found in the expression');
            }
            usort($matches[0], fn($a, $b) => $b[1] <=> $a[1]);
            foreach ($matches[0] as $match) {
                list($name, $offset) = $match;
                $value = $this->_n7e($this->_bindings[$name]);
                $_expr = substr_replace($_expr, $value, $offset, strlen($name));
            }

            $this->_cached = $_expr;
        }
        while (false);

        return $this->_cached;
    }
}
