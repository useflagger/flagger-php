<?php

namespace Flagger\Settings\Request\Entities\Context;

class Value
{
    public string $value;

    function __construct(string $value)
    {
        $this->value = $value;
    }
}