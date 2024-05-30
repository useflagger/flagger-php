<?php

namespace Flagger\Flags\Request\Entities\Context;

class Value
{
    public string $value;

    function __construct(string $value)
    {
        $this->value = $value;
    }
}