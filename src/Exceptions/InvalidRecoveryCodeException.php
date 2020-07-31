<?php

namespace Astrotomic\AuthRecoveryCodes\Exceptions;

use Astrotomic\AuthRecoveryCodes\Recoverable;
use OutOfBoundsException;
use Throwable;

class InvalidRecoveryCodeException extends OutOfBoundsException
{
    protected ?Recoverable $recoverable;
    protected ?string $recoveryCode;

    public static function make(Recoverable $recoverable, string $recoveryCode): self
    {
        return (new static())
            ->setRecoverable($recoverable)
            ->setRecoveryCode($recoveryCode);
    }

    public function setRecoverable(Recoverable $recoverable): self
    {
        $this->recoverable = $recoverable;

        $this->message = sprintf('No matching recovery token found for %s #%s', get_class($recoverable), $recoverable->getKey());

        return $this;
    }

    public function getRecoverable(): ?Recoverable
    {
        return $this->recoverable;
    }

    public function getRecoveryCode(): ?string
    {
        return $this->recoveryCode;
    }

    public function setRecoveryCode(?string $recoveryCode): self
    {
        $this->recoveryCode = $recoveryCode;

        return $this;
    }
}
