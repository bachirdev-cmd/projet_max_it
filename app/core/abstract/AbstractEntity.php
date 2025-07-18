<?php

namespace App\Core\Abstract;

abstract class AbstractEntity{
    abstract public function toObject(array $data):static;
    abstract public function toArray() : array ;
    public function toJson(): string {
        return json_encode($static::toArray(), JSON_PRETTY_PRINT);
    }
}