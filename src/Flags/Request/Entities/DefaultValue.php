<?php

namespace Flagger\Flags\Request\Entities;

use \Flagger\Flags\Response\Entities\Flag;

class DefaultValue
{
    public string $key;
    public string $type;
    public string|bool|int|float $value;

    function __construct(string $key, string $type, string|bool|int|float $value) {
        $this->key = $key;
        $this->type = $type;
        $this->value = $value;
    }

    function toArray() : array {
        return [
            'key' => $this->key,
            'type' => $this->type,
            'value' => $this->value,
        ];
    }

    function toJson() : String {
        return json_encode($this->toArray());
    }

    function toFlag() : Flag {
        return new Flag($this->key, '', $this->type, (object)array($this->type => $this->value));
    }
}