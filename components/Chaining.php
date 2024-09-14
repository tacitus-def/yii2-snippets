<?php

namespace app\components;

class Chaining {
        private $value;

        private function __construct(&$value) {
                $this->value = &$value;
        }

        public function __call($func, $args) {
                list($callback, $position) = explode(':', $func) + [null, 0];
                $data = array_merge(array_splice($args, 0, $position), [$this->value], $args);
                $this->value = call_user_func_array($callback, $data);

                return $this;
        }

        public static function ref(&$value) {
            return new self($value);
        }

        public static function set($value) {
            return new self($value);
        }

        public function get() {
            return $this->value;
        }
}
