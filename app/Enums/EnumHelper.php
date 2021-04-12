<?php
namespace App\Enums;

class EnumHelper {
    private $class;
    public function __construct($className)
    {
        try {
            $this->class = new \ReflectionClass($className);
        } catch (ReflectionException $exception) {
            throw $exception;
        }
    }

    function getProperties() {
        return array_keys($this->class->getConstants());
    }

    function getValues() {
        return array_values($this->class->getConstants());
    }

    /**
     * @param $value
     * @return false|int|string
     */
    function getKey($value) {
        return array_search($value, $this->class->getConstants());
    }

    function getClass() {
        return $this->class;
    }
}
