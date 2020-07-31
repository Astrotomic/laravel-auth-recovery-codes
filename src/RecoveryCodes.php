<?php

namespace Astrotomic\AuthRecoveryCodes;

use Astrotomic\ConditionalProxy\HasConditionalCalls;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use PragmaRX\Recovery\Recovery;

class RecoveryCodes extends Recovery implements Arrayable, Jsonable
{
    use HasConditionalCalls;

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
