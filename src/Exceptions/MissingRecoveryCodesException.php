<?php

namespace Astrotomic\AuthRecoveryCodes\Exceptions;

use Astrotomic\AuthRecoveryCodes\Recoverable;
use OutOfBoundsException;

class MissingRecoveryCodesException extends OutOfBoundsException
{
    protected ?Recoverable $recoverable;

    public static function make(Recoverable $model): self
    {
        return (new static())->setRecoverable($model);
    }

    public function setRecoverable(Recoverable $model): self
    {
        $this->recoverable = $model;

        $this->message = sprintf('No recovery codes found for %s #%s', get_class($model), $model->getKey());

        return $this;
    }

    public function getRecoverable(): ?Recoverable
    {
        return $this->recoverable;
    }
}
