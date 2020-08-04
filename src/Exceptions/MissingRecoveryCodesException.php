<?php

namespace Astrotomic\AuthRecoveryCodes\Exceptions;

use OutOfBoundsException;

class MissingRecoveryCodesException extends OutOfBoundsException
{
    protected $recoverable;

    public static function make($model): self
    {
        return (new static())->setRecoverable($model);
    }

    public function setRecoverable($model): self
    {
        $this->recoverable = $model;

        $this->message = sprintf('No recovery codes found for %s #%s', get_class($model), $model->getKey());

        return $this;
    }

    public function getRecoverable()
    {
        return $this->recoverable;
    }
}
