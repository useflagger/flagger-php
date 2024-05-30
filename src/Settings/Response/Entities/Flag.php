<?php

namespace Flagger\Flags\Response\Entities;

use \Flagger\Exceptions\MalformedResponseException;

class Flag
{
    static public function map(string|null $json) : Flag
    {
        if ($json == null)
        {
            throw new MalformedResponseException();
        }

        $source = json_decode($json)->data->flag;
        return new Flag($source->key, $source->name, $source->type, $source->value);
    }

    public string $key;
    public string $name;
    public string $type;
    private Object $value;

    function __construct(string $key, string $name, string $type, Object $value) {
        $this->key = $key;
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
    }

    function getValue() : bool|float|int|string {
        switch($this->type) {
            case 'boolean':
                return $this->getBoolean();
            case 'number':
                return $this->getNumber();
            case 'string':
            default:
                return $this->getString();
        }
    }

    private function getBoolean() : bool {
        return (bool)($this->value->boolean);
    }

    private function getNumber() : float|int {
        $value = $this->value->number;
        return is_int($value) ? (int)$value : (float)$value;
    }

    private function getString() : string {
        return $this->value->string;
    }
}